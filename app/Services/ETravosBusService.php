<?php

namespace App\Services;

use App\Models\BusApiLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ETravosBusService
{
    protected $baseUrl;
    protected $username;
    protected $password;
    protected $accessToken;
    protected $tokenExpiry;
    protected $traceId;
    protected $maxRetries = 2;

    public function __construct()
    {
        /*
         * .env:
         * ETRAVOS_BASE_URL=https://stagingbapi.etravos.in/api/
         *
         * rtrim removes the final /
         */
        $this->baseUrl = rtrim(
            config('services.etravos.base_url', 'https://stagingbapi.etravos.in/api'),
            '/'
        );

        $this->username = config(
            'services.etravos.username',
            'phoenixtt'
        );

        $this->password = config(
            'services.etravos.password',
            ''
        );

        $this->loadTokenFromCache();
    }

    /**
     * Load cached eTravos token.
     */
    protected function loadTokenFromCache()
    {
        if (Cache::has('etravos_access_token')) {
            $this->accessToken = Cache::get('etravos_access_token');
            $this->tokenExpiry = Cache::get('etravos_token_expiry');
        }
    }

    /**
     * Check whether token is expired or will expire
     * within the next 5 minutes.
     */
    protected function isTokenExpired()
    {
        if (empty($this->accessToken)) {
            return true;
        }

        if (empty($this->tokenExpiry)) {
            return true;
        }

        try {
            $expiryTime = Carbon::parse($this->tokenExpiry);

            return $expiryTime->copy()->subMinutes(5)->isPast();
        } catch (\Exception $e) {
            Log::warning('Invalid eTravos token expiry', [
                'error' => $e->getMessage(),
            ]);

            return true;
        }
    }

    /**
     * Authenticate with eTravos.
     *
     * Endpoint:
     * POST /api/bus/login
     */
    public function authenticate()
    {
        /*
         * Reuse existing token if still valid.
         */
        if (!$this->isTokenExpired() && $this->accessToken) {
            return $this->accessToken;
        }

        $endpoint = '/bus/login';

        $payload = [
            'username' => $this->username,
            'password' => $this->password,
        ];

        Log::info('Authenticating with eTravos API', [
            'username' => $this->username,
        ]);

        $response = $this->sendRawRequest(
            $endpoint,
            $payload,
            'POST',
            true
        );

        if ($response && isset($response['accessToken'])) {
            $this->accessToken = $response['accessToken'];

            /*
             * Extract JWT expiry if possible.
             * Otherwise use 23 hours.
             */
            $expiry = $this->extractTokenExpiry(
                $this->accessToken
            );

            Cache::put(
                'etravos_access_token',
                $this->accessToken,
                $expiry
            );

            Cache::put(
                'etravos_token_expiry',
                Carbon::now()->addSeconds($expiry),
                $expiry
            );

            $this->tokenExpiry = Carbon::now()->addSeconds($expiry);

            Log::info('eTravos authentication successful', [
                'expires_in' => $expiry . ' seconds',
            ]);

            return $this->accessToken;
        }

        Log::error('eTravos authentication failed', [
            'response' => $response,
        ]);

        return null;
    }

    /**
     * Extract expiry from JWT.
     */
    protected function extractTokenExpiry($token)
    {
        try {
            $parts = explode('.', $token);

            if (count($parts) === 3) {
                /*
                 * JWT uses Base64URL rather than normal Base64.
                 */
                $payloadPart = $parts[1];

                $payloadPart = strtr(
                    $payloadPart,
                    '-_',
                    '+/'
                );

                $padding = strlen($payloadPart) % 4;

                if ($padding > 0) {
                    $payloadPart .= str_repeat(
                        '=',
                        4 - $padding
                    );
                }

                $payload = json_decode(
                    base64_decode($payloadPart),
                    true
                );

                if (isset($payload['exp'])) {
                    $expiryTimestamp = (int) $payload['exp'];

                    $expiry = $expiryTimestamp - time();

                    if ($expiry > 0) {
                        return $expiry;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning(
                'Could not extract eTravos token expiry',
                [
                    'error' => $e->getMessage(),
                ]
            );
        }

        /*
         * Default: 23 hours.
         */
        return 82800;
    }

    /**
     * Send raw HTTP request.
     *
     * Used for login and authenticated requests.
     */
    protected function sendRawRequest(
        $endpoint,
        $data = [],
        $method = 'POST',
        $isLogin = false,
        $retryCount = 0
    ) {
        /*
         * Because ETRAVOS_BASE_URL already contains /api:
         *
         * https://stagingbapi.etravos.in/api
         *
         * + /bus/search
         *
         * becomes:
         *
         * https://stagingbapi.etravos.in/api/bus/search
         */
        $url = $this->baseUrl . '/' . ltrim(
            $endpoint,
            '/'
        );

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        /*
         * Add Bearer token to every request except login.
         */
        if (!$isLogin && $this->accessToken) {
            $headers['Authorization'] =
                'Bearer ' . $this->accessToken;
        }

        $startTime = microtime(true);

        $responseData = null;
        $statusCode = 500;
        $traceId = null;

        try {
            Log::info('eTravos API Request', [
                'endpoint' => $endpoint,
                'method' => strtoupper($method),
                'url' => $url,
                'has_token' => !empty($this->accessToken),
                'is_login' => $isLogin,
            ]);

            /*
             * Laravel HTTP client methods are dynamic:
             *
             * ->post()
             * ->get()
             * etc.
             */
            $response = Http::withHeaders($headers)
                ->timeout(60)
                ->{$method}($url, $data);

            $responseTime = microtime(true) - $startTime;

            $responseData = $response->json();

            $statusCode = $response->status();

            /*
             * Store trace ID from API response.
             */
            if (is_array($responseData)) {
                $traceId = $responseData['traceId'] ?? null;
            }

            /*
             * If token expired, authenticate again and retry.
             */
            if (
                $statusCode === 401 &&
                !$isLogin &&
                $retryCount < $this->maxRetries
            ) {
                Log::warning(
                    'eTravos token expired. Refreshing token.',
                    [
                        'endpoint' => $endpoint,
                        'retry' => $retryCount + 1,
                    ]
                );

                $this->clearToken();

                if ($this->authenticate()) {
                    return $this->sendRawRequest(
                        $endpoint,
                        $data,
                        $method,
                        false,
                        $retryCount + 1
                    );
                }
            }

            /*
             * Log API call.
             */
            $this->logApiCall(
                $endpoint,
                $method,
                $data,
                $responseData,
                $statusCode,
                $traceId,
                $responseTime,
                $statusCode >= 400,
                $this->accessToken
            );

            /*
             * Successful HTTP response.
             */
            if ($response->successful()) {
                return $responseData;
            }

            Log::error('eTravos API Error', [
                'endpoint' => $endpoint,
                'url' => $url,
                'status' => $statusCode,
                'body' => $response->body(),
            ]);

            return [
                'error' => true,
                'message' => is_array($responseData)
                    ? (
                        $responseData['message']
                        ?? $responseData['error']['desc']
                        ?? 'API request failed'
                    )
                    : 'API request failed',
                'status' => $statusCode,
                'traceId' => $traceId,
                'response' => $responseData,
            ];

        } catch (\Exception $e) {

            $responseTime = microtime(true) - $startTime;

            $errorResponse = [
                'error' => $e->getMessage(),
            ];

            $this->logApiCall(
                $endpoint,
                $method,
                $data,
                $errorResponse,
                500,
                null,
                $responseTime,
                true,
                $this->accessToken
            );

            Log::error('eTravos API Exception', [
                'endpoint' => $endpoint,
                'url' => $url,
                'message' => $e->getMessage(),
            ]);

            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send authenticated request.
     */
    protected function sendRequest(
        $endpoint,
        $data = [],
        $method = 'POST'
    ) {
        /*
         * Authenticate automatically if token is missing/expired.
         */
        if ($this->isTokenExpired()) {

            Log::info(
                'eTravos token expired or missing. Authenticating.'
            );

            if (!$this->authenticate()) {
                return [
                    'error' => true,
                    'message' => 'eTravos authentication failed',
                ];
            }
        }

        return $this->sendRawRequest(
            $endpoint,
            $data,
            $method,
            false
        );
    }

    /**
     * Log API call to database.
     */
    protected function logApiCall(
        $endpoint,
        $method,
        $requestData,
        $responseData,
        $statusCode,
        $traceId = null,
        $responseTime = null,
        $isError = false,
        $token = null
    ) {
        try {

            /*
             * Do not store the actual password in API logs.
             */
            $safeRequestData = $requestData;

            if (
                is_array($safeRequestData) &&
                isset($safeRequestData['password'])
            ) {
                $safeRequestData['password'] = '********';
            }

            $logData = [
                'endpoint' => $endpoint,
                'method' => strtoupper($method),
                'request_data' => $safeRequestData,
                'response_data' => $responseData,
                'status_code' => $statusCode,
                'trace_id' => $traceId,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'error_message' => $isError
                    ? (
                        is_array($responseData)
                            ? (
                                $responseData['message']
                                ?? $responseData['error']['desc']
                                ?? null
                            )
                            : null
                    )
                    : null,
                'response_time' => $responseTime,
                'is_error' => $isError,

                /*
                 * Keep only a small masked token portion
                 * if your existing database column requires it.
                 */
                'token_used' => $token
                    ? substr($token, 0, 30) . '...'
                    : null,
            ];

            BusApiLog::create($logData);

        } catch (\Exception $e) {

            Log::error(
                'Failed to log eTravos API call',
                [
                    'error' => $e->getMessage(),
                    'endpoint' => $endpoint,
                ]
            );
        }
    }

    /**
     * Get API logs.
     */
    public function getApiLogs(
        $filters = [],
        $limit = 100
    ) {
        $query = BusApiLog::query();

        if (isset($filters['endpoint'])) {
            $query->where(
                'endpoint',
                'like',
                '%' . $filters['endpoint'] . '%'
            );
        }

        if (isset($filters['status_code'])) {
            $query->where(
                'status_code',
                $filters['status_code']
            );
        }

        if (isset($filters['is_error'])) {
            $query->where(
                'is_error',
                $filters['is_error']
            );
        }

        if (isset($filters['trace_id'])) {
            $query->where(
                'trace_id',
                $filters['trace_id']
            );
        }

        if (isset($filters['date_from'])) {
            $query->whereDate(
                'created_at',
                '>=',
                $filters['date_from']
            );
        }

        if (isset($filters['date_to'])) {
            $query->whereDate(
                'created_at',
                '<=',
                $filters['date_to']
            );
        }

        return $query
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Delete old API logs.
     */
    public function cleanLogs($days = 30)
    {
        return BusApiLog::where(
            'created_at',
            '<',
            Carbon::now()->subDays($days)
        )->delete();
    }

    /**
     * SEARCH BUSES
     *
     * POST /api/bus/search
     */
    public function searchBuses(
        $originCode,
        $destinationCode,
        $departureDate
    ) {
        $departureDateTime = Carbon::parse(
            $departureDate
        )->format('Y-m-d\T00:00:00');

        $payload = [
            'trips' => [
                [
                    'src' => (string) $originCode,
                    'dst' => (string) $destinationCode,
                    'doj' => $departureDateTime,
                ],
            ],
            'ext' => [
                'timeout' => 60000,
                'filters' => true,
                'tags' => [],
            ],
        ];

        Log::info('eTravos Search Payload', [
            'payload' => $payload,
        ]);

        return $this->sendRequest(
            '/bus/search',
            $payload,
            'POST'
        );
    }

    /**
     * GET CITIES
     *
     * Existing city functionality preserved.
     */
    public function getCities()
    {
        if (Cache::has('etravos_cities')) {
            return Cache::get('etravos_cities');
        }

        $response = $this->sendRequest(
            '/cdn/cities',
            [],
            'GET'
        );

        if (
            isset($response['error']) &&
            $response['error'] === true
        ) {
            Log::error(
                'Failed to fetch cities from eTravos',
                [
                    'response' => $response,
                ]
            );

            return $this->getFallbackCities();
        }

        if (isset($response['cities'])) {

            Cache::put(
                'etravos_cities',
                $response['cities'],
                now()->addHours(24)
            );

            return $response['cities'];
        }

        return $this->getFallbackCities();
    }

    /**
     * Fallback cities.
     */
    protected function getFallbackCities()
    {
        return [
            [
                'code' => '8466',
                'name' => 'Vijayawada',
                'state' => 'Andhra Pradesh',
            ],
            [
                'code' => '2863',
                'name' => 'Hyderabad',
                'state' => 'Telangana',
            ],
            [
                'code' => '1780',
                'name' => 'Delhi',
                'state' => 'Delhi',
            ],
            [
                'code' => '1361',
                'name' => 'Chandigarh',
                'state' => 'Chandigarh',
            ],
            [
                'code' => 'JPR',
                'name' => 'Jaipur',
                'state' => 'Rajasthan',
            ],
        ];
    }

    /**
     * SEAT LAYOUT
     *
     * POST /api/bus/select
     *
     * Swagger request:
     *
     * {
     *   "traceId": "...",
     *   "busId": "...",
     *   "bpid": "...",
     *   "dpid": "...",
     *   "ext": {
     *      "timeout": 60000,
     *      "layout": "Horizontal"
     *   }
     * }
     */
    public function getSeatLayout(
        $traceId,
        $busId,
        $boardingPointId,
        $droppingPointId,
        $layout = 'Horizontal'
    ) {
        $payload = [
            'traceId' => (string) $traceId,

            'busId' => (string) $busId,

            'bpid' => (string) $boardingPointId,

            'dpid' => (string) $droppingPointId,

            'ext' => [
                'timeout' => 60000,
                'layout' => $layout,
            ],
        ];

        Log::info(
            'eTravos Seat Layout Request',
            [
                'busId' => $busId,
                'bpid' => $boardingPointId,
                'dpid' => $droppingPointId,
                'traceId' => $traceId,
            ]
        );

        return $this->sendRequest(
            '/bus/select',
            $payload,
            'POST'
        );
    }

    /**
     * BLOCK TICKET
     *
     * POST /api/bus/block
     *
     * The refNo returned by this method is required
     * for the BOOK operation.
     */
    public function blockTicket(
        $traceId,
        $tripKey,
        $boardingPointId,
        $droppingPointId,
        $email,
        $phone,
        $passengers
    ) {
        $payload = [
            'traceId' => (string) $traceId,

            'trips' => [
                [
                    'tripKey' => (string) $tripKey,
                    'bpid' => (string) $boardingPointId,
                    'dpid' => (string) $droppingPointId,
                ],
            ],

            'poc' => [
                'email' => (string) $email,
                'phone' => (string) $phone,
            ],

            'pax' => $passengers,

            'ext' => [
                'timeout' => 60000,
            ],
        ];

        Log::info(
            'eTravos Block Request',
            [
                'traceId' => $traceId,
                'tripKey' => $tripKey,
                'bpid' => $boardingPointId,
                'dpid' => $droppingPointId,
                'passenger_count' => count($passengers),
            ]
        );

       
$response = $this->sendRequest('/bus/block', $payload, 'POST');

$tripResult = $response['trips'][0] ?? [];
$tripError = $tripResult['error'] ?? null;

\Illuminate\Support\Facades\Log::info('eTravos BLOCK diagnostic', [
    'traceId' => $response['traceId'] ?? $traceId,
    'trip_status' => $tripResult['status'] ?? null,
    'trip_error_code' => is_array($tripError)
        ? ($tripError['code'] ?? null)
        : null,
    'trip_error_description' => is_array($tripError)
        ? ($tripError['desc'] ?? null)
        : null,
    'has_refNo' => !empty($tripResult['refNo'])
        || !empty($response['refNo']),
]);

return $response;
    }

    /**
     * BOOK TICKET
     *
     * POST /api/bus/book
     *
     * IMPORTANT:
     * This receives refNo from BLOCK.
     */
    public function bookTicket($refNo)
    {
        $payload = [
            'trips' => [
                [
                    'refNo' => (string) $refNo,
                ],
            ],
        ];

        Log::info(
            'eTravos Book Request',
            [
                'refNo' => $refNo,
            ]
        );

        return $this->sendRequest(
            '/bus/book',
            $payload,
            'POST'
        );
    }

    /**
     * GET TICKET DETAILS
     *
     * POST /api/bus/ticket
     */
    public function getTicketDetails($ticketNumber)
    {
        $payload = [
            'tktNo' => (string) $ticketNumber,
        ];

        Log::info(
            'eTravos Ticket Details Request',
            [
                'tktNo' => $ticketNumber,
            ]
        );

        return $this->sendRequest(
            '/bus/ticket',
            $payload,
            'POST'
        );
    }

    /**
     * CANCEL TICKET / SELECTED SEATS
     *
     * POST /api/bus/cancel
     *
     * Swagger request:
     *
     * {
     *   "tktNo": "100430843",
     *   "seats": [
     *      "U1B"
     *   ]
     * }
     */
    public function cancelTicket(
        $ticketNumber,
        $seatIds
    ) {
        /*
         * Make sure seats are always an array.
         */
        if (!is_array($seatIds)) {
            $seatIds = [$seatIds];
        }

        $payload = [
            'tktNo' => (string) $ticketNumber,
            'seats' => array_values($seatIds),
        ];

        Log::info(
            'eTravos Cancel Request',
            [
                'tktNo' => $ticketNumber,
                'seats' => $seatIds,
            ]
        );

        return $this->sendRequest(
            '/bus/cancel',
            $payload,
            'POST'
        );
    }

    /**
     * Get token status.
     */
    public function getTokenStatus()
    {
        $isValid =
            !$this->isTokenExpired() &&
            !empty($this->accessToken);

        return [
            'has_token' => !empty($this->accessToken),
            'is_valid' => $isValid,
            'expires_at' => $this->tokenExpiry,
            'expires_in' => $this->tokenExpiry
                ? Carbon::now()->diffInSeconds(
                    $this->tokenExpiry
                )
                : null,
        ];
    }

    /**
     * Clear eTravos token.
     */
    public function clearToken()
    {
        Cache::forget(
            'etravos_access_token'
        );

        Cache::forget(
            'etravos_token_expiry'
        );

        $this->accessToken = null;
        $this->tokenExpiry = null;

        return true;
    }
}