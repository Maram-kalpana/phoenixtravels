<?php

namespace App\Http\Controllers;

use App\Services\ETravosBusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class BusController extends Controller
{
    protected $etravos;

    public function __construct(ETravosBusService $etravos)
    {
        $this->etravos = $etravos;
    }

    /**
     * Display bus search page
     */
    public function index()
    {
        return view('frontend.bus-list');
    }

    /**
     * Get cities list with caching
     */
    public function getCities(Request $request)
    {
        try {
            // Cache cities for 24 hours to reduce API calls
            $cities = Cache::remember('etravos_cities_formatted', 86400, function () {
                $cities = $this->etravos->getCities();
                return $this->formatCities($cities);
            });
            
            return response()->json([
                'success' => true,
                'data' => $cities,
                'cached' => true
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to fetch cities', ['error' => $e->getMessage()]);
            
            // Return cached cities if available
            if (Cache::has('etravos_cities_formatted')) {
                return response()->json([
                    'success' => true,
                    'data' => Cache::get('etravos_cities_formatted'),
                    'cached' => true
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch cities',
                'data' => $this->getDefaultCities()
            ], 500);
        }
    }

    /**
     * Format cities for frontend
     */
    private function formatCities($cities)
    {
        $formatted = [];
        
        if (empty($cities)) {
            return $this->getDefaultCities();
        }
        
        foreach ($cities as $city) {
            $formatted[] = [
                'id' => $city['code'] ?? $city['id'] ?? '',
                'name' => $city['name'] ?? '',
                'state' => $city['state'] ?? $city['region'] ?? '',
                'country' => $city['country'] ?? 'India',
                'code' => $city['code'] ?? ''
            ];
        }
        
        return !empty($formatted) ? $formatted : $this->getDefaultCities();
    }

    /**
     * Get default cities for fallback
     */
    private function getDefaultCities()
    {
        return [
            ['id' => '8466', 'name' => 'Vijayawada', 'state' => 'Andhra Pradesh', 'country' => 'India', 'code' => '8466'],
            ['id' => '2863', 'name' => 'Hyderabad', 'state' => 'Telangana', 'country' => 'India', 'code' => '2863'],
            ['id' => '1780', 'name' => 'Delhi', 'state' => 'Delhi', 'country' => 'India', 'code' => '1780'],
            ['id' => '1361', 'name' => 'Chandigarh', 'state' => 'Chandigarh', 'country' => 'India', 'code' => '1361'],
            ['id' => 'JPR', 'name' => 'Jaipur', 'state' => 'Rajasthan', 'country' => 'India', 'code' => 'JPR'],
        ];
    }

    /**
     * Search for buses via AJAX with pagination
     */
    public function searchBuses(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'origin' => 'required|string|max:255',
                'destination' => 'required|string|max:255',
                'origin_code' => 'required|string',
                'destination_code' => 'required|string',
                'departure_date' => 'required|date|after_or_equal:today',
                'return_date' => 'nullable|date|after:departure_date',
                'trip_type' => 'required|in:oneway,roundtrip',
                'adults' => 'nullable|integer|min:1|max:10',
                'children' => 'nullable|integer|min:0|max:5',
                'infants' => 'nullable|integer|min:0|max:3',
                'page' => 'nullable|integer|min:1',
                'per_page' => 'nullable|integer|min:1|max:50'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get pagination parameters
            $page = $request->page ?? 1;
            $perPage = $request->per_page ?? 10;
            $offset = ($page - 1) * $perPage;

            // Calculate total passengers
            $totalPassengers = ($request->adults ?? 1) + ($request->children ?? 0) + ($request->infants ?? 0);

            Log::info('Bus Search Request', [
                'origin' => $request->origin,
                'destination' => $request->destination,
                'origin_code' => $request->origin_code,
                'destination_code' => $request->destination_code,
                'departure_date' => $request->departure_date,
                'trip_type' => $request->trip_type,
                'passengers' => $totalPassengers,
                'page' => $page,
                'per_page' => $perPage
            ]);

            // Search buses using codes
            $result = $this->etravos->searchBuses(
                $request->origin_code,
                $request->destination_code,
                $request->departure_date
            );

            Log::info('Search Response', ['result' => $result]);

            // Check for errors
            if (isset($result['error']) && $result['error'] === true) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'No buses found for the selected route',
                    'trace_id' => $result['traceId'] ?? null
                ], 404);
            }

            // Format response for frontend
            $formattedBuses = $this->formatBusResults($result, $request->all());

            // Apply filters if any
            if ($request->has('max_price') && $request->max_price > 0) {
                $formattedBuses = array_filter($formattedBuses, function($bus) use ($request) {
                    return $bus['fare'] <= $request->max_price;
                });
            }

            if ($request->has('bus_type') && $request->bus_type !== 'all') {
                $formattedBuses = array_filter($formattedBuses, function($bus) use ($request) {
                    return strtolower($bus['bus_type']) === str_replace('_', ' ', $request->bus_type);
                });
            }

            // Sort results
            if ($request->has('sort_by')) {
                $formattedBuses = $this->sortBuses($formattedBuses, $request->sort_by);
            }

            // Convert to array and apply pagination
            $formattedBuses = array_values($formattedBuses);
            $total = count($formattedBuses);
            $paginatedBuses = array_slice($formattedBuses, $offset, $perPage);

            return response()->json([
                'success' => true,
                'data' => $paginatedBuses,
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => ceil($total / $perPage),
                'trace_id' => $result['traceId'] ?? null,
                'search_params' => $request->all()
            ]);

        } catch (\Exception $e) {
            Log::error('Bus Search Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while searching for buses. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format bus search results for frontend
     */
    private function formatBusResults($apiResponse, $searchParams)
    {
        $formattedBuses = [];
        
        // Extract trips and buses from response
        $trips = $apiResponse['trips'] ?? [];
        
        if (empty($trips)) {
            Log::warning('No trips found in API response');
            return $formattedBuses;
        }

        foreach ($trips as $trip) {
            $buses = $trip['buses'] ?? [];
            $summary = $trip['summary'] ?? [];
            
            foreach ($buses as $bus) {
                $busData = $this->extractBusData(
                    $bus,
                    $searchParams,
                    $summary,
                    $apiResponse['traceId'] ?? null
                );
                if ($busData) {
                    $formattedBuses[] = $busData;
                }
            }
        }

        return $formattedBuses;
    }

    /**
     * Extract bus data from API response
     */
    private function extractBusData($bus, $searchParams, $summary, $traceId = null)
{
    try {
        $fare = $bus['fares'][0] ?? [];
        $boarding = $bus['boarding'][0] ?? [];
        $dropping = $bus['dropping'][0] ?? [];

        return [
            'id' => $bus['id'] ?? uniqid(),
            'name' => $bus['name'] ?? 'Bus',
            'operator' => $bus['name'] ?? 'Unknown Operator',
            'bus_type' => $bus['type'] ?? 'Seater',
            'bus_number' => $bus['nmbr'] ?? '',

            'departure_time' => $this->formatTime($bus['timeD'] ?? ''),
            'arrival_time' => $this->formatTime($bus['timeA'] ?? ''),
            'duration' => $this->formatDuration($bus['duration'] ?? 0),
            'bus_start_time' => $bus['busStartTime'] ?? '',

            'origin' => $searchParams['origin'] ?? '',
            'destination' => $searchParams['destination'] ?? '',
            'origin_code' => $searchParams['origin_code'] ?? '',
            'destination_code' => $searchParams['destination_code'] ?? '',

            'fare' => floatval($fare['total'] ?? 0),
            'base_fare' => floatval($fare['base'] ?? 0),
            'tax' => floatval($fare['gst'] ?? 0),
            'commission' => floatval($fare['commission'] ?? 0),

            'available_seats' => intval($bus['seats']['avlAll'] ?? 0),

            'amenities' => $bus['amenities'] ?? [],

            'ac' => $bus['tags']['ac'] ?? false,
            'non_ac' => $bus['tags']['nonAc'] ?? false,
            'seater' => $bus['tags']['seater'] ?? false,
            'sleeper' => $bus['tags']['sleeper'] ?? false,
            'single_ladies' => $bus['tags']['singleLadies'] ?? false,

            'cancel_policy' => $bus['policy']['cancel'] ?? [],

            'boarding_points' => $bus['boarding'] ?? [],
            'dropping_points' => $bus['dropping'] ?? [],

            'boarding_point_id' => $boarding['id'] ?? '',
            'dropping_point_id' => $dropping['id'] ?? '',

            'api_id' => $bus['apiId'] ?? 0,
            'ecode' => $bus['ecode'] ?? '',
            'verified' => $bus['verified'] ?? false,
            'partial_cancellation' => $bus['partialCancellation'] ?? false,

            'trip_id' => $bus['id'] ?? '',

            // Important: traceId belongs to the SEARCH response.
            'trace_id' => $traceId,
        ];

    } catch (\Exception $e) {
        Log::error('Error extracting bus data', [
            'bus' => $bus,
            'error' => $e->getMessage()
        ]);

        return null;
    }
}

    /**
     * Format time from datetime string
     */
    private function formatTime($datetime)
    {
        if (empty($datetime)) return '00:00';
        try {
            return Carbon::parse($datetime)->format('h:i A');
        } catch (\Exception $e) {
            return '00:00';
        }
    }

    /**
     * Format duration from minutes
     */
    private function formatDuration($minutes)
    {
        if ($minutes <= 0) return '0h 0m';
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        return $hours . 'h ' . $mins . 'm';
    }

    /**
     * Sort buses
     */
    private function sortBuses($buses, $sortBy)
    {
        usort($buses, function($a, $b) use ($sortBy) {
            switch ($sortBy) {
                case 'price_low':
                    return $a['fare'] <=> $b['fare'];
                case 'price_high':
                    return $b['fare'] <=> $a['fare'];
                case 'rating':
                    return ($b['rating'] ?? 0) <=> ($a['rating'] ?? 0);
                case 'recommended':
                default:
                    // Sort by AC first, then by price
                    if ($a['ac'] != $b['ac']) {
                        return $b['ac'] <=> $a['ac'];
                    }
                    return $a['fare'] <=> $b['fare'];
            }
        });

        return $buses;
    }

    /**
     * Display seat selection page
     */
    public function showSeats($busId)
    {
        return view('frontend.seat-selection', compact('busId'));
    }

    /**
     * Get seat layout from API
     */
    public function getSeatLayout(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'busId'   => 'required|string',
            'bpid'    => 'required|string',
            'dpid'    => 'required|string',
            'traceId' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        Log::info('Seat Layout Request', [
            'traceId' => $request->traceId,
            'busId'   => $request->busId,
            'bpid'    => $request->bpid,
            'dpid'    => $request->dpid,
        ]);

        /*
         * IMPORTANT:
         * Do NOT call sendRequest() here.
         * sendRequest() is protected.
         *
         * Use the public getSeatLayout() method.
         */
        $result = $this->etravos->getSeatLayout(
            $request->traceId,
            $request->busId,
            $request->bpid,
            $request->dpid
        );

        Log::info('Seat Layout Response', [
            'busId'     => $request->busId,
            'traceId'   => $request->traceId,
            'seatCount' => count($result['seats'] ?? []),
            'error'     => $result['error'] ?? null,
        ]);

        /*
         * eTravos can return an error object.
         */
        if (
            isset($result['error']) &&
            is_array($result['error']) &&
            !empty($result['error'])
        ) {
            return response()->json([
                'success' => false,
                'message' => $result['error']['desc']
                    ?? 'Seat layout is not available for this bus.',
                'data' => $result,
            ]);
        }

        /*
         * Also handle our service-level error response.
         */
        if (
            isset($result['error']) &&
            $result['error'] === true
        ) {
            return response()->json([
                'success' => false,
                'message' => $result['message']
                    ?? 'Failed to fetch seat layout.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data'    => $result,
        ]);

    } catch (\Throwable $e) {

        Log::error('Seat Layout Error', [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch seat layout.',
            'error'   => $e->getMessage(),
        ], 500);
    }
}
/**
 * Block selected seats with eTravos
 */
public function blockTicket(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'traceId' => 'required|string',
            'tripKey' => 'required|string',
            'bpid' => 'required|string',
            'dpid' => 'required|string',

            'email' => 'required|email',
            'phone' => 'required|string|min:10|max:15',

            'passengers' => 'required|array|min:1',
            'passengers.*.name' => 'required|string|max:100',
            'passengers.*.gender' => 'required|in:M,F',
            'passengers.*.age' => 'required|integer|min:1|max:100',
            'passengers.*.seatIds' => 'required|array|min:1',
            'passengers.*.seatIds.*' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide all required booking details.',
                'errors' => $validator->errors(),
            ], 422);
        }

        Log::info('eTravos BLOCK request', [
            'traceId' => $request->traceId,
            'tripKey' => $request->tripKey,
            'bpid' => $request->bpid,
            'dpid' => $request->dpid,
            'email' => $request->email,
            'phone' => $request->phone,
            'passenger_count' => count($request->passengers),
        ]);

        $result = $this->etravos->blockTicket(
            $request->traceId,
            $request->tripKey,
            $request->bpid,
            $request->dpid,
            $request->email,
            $request->phone,
            $request->passengers
        );

        Log::info('eTravos BLOCK response', [
            'traceId' => $request->traceId,
            'tripKey' => $request->tripKey,
            'error' => $result['error'] ?? null,
            'refNo' => $result['refNo'] ?? null,
        ]);

        /*
         * Handle service-level errors
         */
        if (
            isset($result['error']) &&
            $result['error'] === true
        ) {
            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Unable to block the selected seats.',
            ], 500);
        }

        /*
         * Handle eTravos API error object
         */
        if (
            isset($result['error']) &&
            is_array($result['error']) &&
            !empty($result['error'])
        ) {
            return response()->json([
                'success' => false,
                'message' => $result['error']['desc']
                    ?? 'Unable to block the selected seats.',
                'data' => $result,
            ], 422);
        }

        /*
         * refNo is required for BOOK.
         */
        $refNo = $result['refNo'] ?? null;

        /*
         * Some responses may put refNo inside trips.
         */
        if (!$refNo && !empty($result['trips'])) {
            foreach ($result['trips'] as $trip) {
                if (!empty($trip['refNo'])) {
                    $refNo = $trip['refNo'];
                    break;
                }
            }
        }

        if (!$refNo) {
            Log::error('eTravos BLOCK succeeded but refNo was missing', [
                'response' => $result,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Seats could not be blocked because the booking reference was not returned.',
                'data' => $result,
            ], 500);
        }

        /*
         * Store the block information in Laravel session.
         * This allows the payment/book step to use refNo later.
         */
        session([
            'etravos_block' => [
                'refNo' => $refNo,
                'traceId' => $request->traceId,
                'tripKey' => $request->tripKey,
                'bpid' => $request->bpid,
                'dpid' => $request->dpid,
                'email' => $request->email,
                'phone' => $request->phone,
                'passengers' => $request->passengers,
                'response' => $result,
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Seats blocked successfully.',
            'refNo' => $refNo,
            'data' => $result,
        ]);

    } catch (\Throwable $e) {

        Log::error('eTravos BLOCK error', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Unable to block the selected seats.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

    /**
     * Get bus details for booking (fallback)
     */
    public function getBusDetails($busId)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $busId,
                    'name' => 'Bus',
                    'operator' => 'Unknown',
                    'bus_type' => 'Seater',
                    'fare' => 0,
                    'available_seats' => 0,
                    'boarding_points' => [],
                    'dropping_points' => []
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch bus details'
            ], 500);
        }
    }
    /**
 * BOOK TICKET after successful payment
 *
 * POST /bus/book
 */
public function bookTicket(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'refNo' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Booking reference number is required.',
                'errors' => $validator->errors(),
            ], 422);
        }

        /*
         * Get the BLOCK information from Laravel session.
         */
        $blockData = session('etravos_block');

        if (empty($blockData)) {
            return response()->json([
                'success' => false,
                'message' => 'No active blocked booking was found. Please start the booking again.',
            ], 400);
        }

        /*
         * Only allow BOOK for the refNo that was actually
         * created by our BLOCK request.
         */
        $sessionRefNo = $blockData['refNo'] ?? null;

        if (
            empty($sessionRefNo) ||
            (string) $sessionRefNo !== (string) $request->refNo
        ) {
            Log::warning('eTravos BOOK refNo mismatch', [
                'request_refNo' => $request->refNo,
                'session_refNo' => $sessionRefNo,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Invalid booking reference number.',
            ], 400);
        }

        Log::info('eTravos BOOK request', [
            'refNo' => $request->refNo,
        ]);

        /*
         * Call eTravos BOOK API.
         */
        $result = $this->etravos->bookTicket(
            $request->refNo
        );

        Log::info('eTravos BOOK response', [
            'refNo' => $request->refNo,
            'error' => $result['error'] ?? null,
            'tktNo' => $result['tktNo'] ?? null,
            'pnrNo' => $result['pnrNo'] ?? null,
        ]);

        /*
         * Handle our service-level error.
         */
        if (
            isset($result['error']) &&
            $result['error'] === true
        ) {
            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Unable to book the ticket.',
                'data' => $result,
            ], 500);
        }

        /*
         * Handle eTravos API error object.
         */
        if (
            isset($result['error']) &&
            is_array($result['error']) &&
            !empty($result['error'])
        ) {
            return response()->json([
                'success' => false,
                'message' => $result['error']['desc']
                    ?? 'Unable to book the ticket.',
                'data' => $result,
            ], 422);
        }

        /*
         * eTravos BOOK should return a ticket number.
         */
        $ticketNumber = $result['tktNo'] ?? null;

        if (!$ticketNumber) {
            Log::error('eTravos BOOK response missing tktNo', [
                'refNo' => $request->refNo,
                'response' => $result,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Booking response did not contain a ticket number.',
                'data' => $result,
            ], 500);
        }

        /*
         * Store successful booking information in Laravel session.
         */
        session([
            'etravos_booking' => [
                'refNo' => $request->refNo,
                'tktNo' => $result['tktNo'] ?? null,
                'pnrNo' => $result['pnrNo'] ?? null,
                'tktKey' => $result['tktKey'] ?? null,
                'operatorNumber' => $result['operatorNumber'] ?? null,
                'response' => $result,
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ticket booked successfully.',
            'tktNo' => $result['tktNo'] ?? null,
            'pnrNo' => $result['pnrNo'] ?? null,
            'tktKey' => $result['tktKey'] ?? null,
            'operatorNumber' => $result['operatorNumber'] ?? null,
            'data' => $result,
        ]);

    } catch (\Throwable $e) {

        Log::error('eTravos BOOK error', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Unable to book the ticket.',
            'error' => $e->getMessage(),
        ], 500);
    }
}


/**
 * GET TICKET DETAILS
 *
 * POST /bus/ticket
 */
public function getTicketDetails(Request $request)
{
    try {

        $validator = Validator::make($request->all(), [
            'tktNo' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket number is required.',
                'errors' => $validator->errors(),
            ], 422);
        }

        Log::info('eTravos TICKET request', [
            'tktNo' => $request->tktNo,
        ]);

        /*
         * Call eTravos TICKET API.
         */
        $result = $this->etravos->getTicketDetails(
            $request->tktNo
        );

        Log::info('eTravos TICKET response', [
            'tktNo' => $request->tktNo,
            'error' => $result['error'] ?? null,
        ]);

        /*
         * Handle our service-level error.
         */
        if (
            isset($result['error']) &&
            $result['error'] === true
        ) {
            return response()->json([
                'success' => false,
                'message' => $result['message']
                    ?? 'Unable to fetch ticket details.',
                'data' => $result,
            ], 500);
        }

        /*
         * Handle eTravos API error object.
         */
        if (
            isset($result['error']) &&
            is_array($result['error']) &&
            !empty($result['error'])
        ) {
            return response()->json([
                'success' => false,
                'message' => $result['error']['desc']
                    ?? 'Unable to fetch ticket details.',
                'data' => $result,
            ], 422);
        }

        /*
         * Save ticket details in Laravel session.
         */
        session([
            'etravos_ticket' => $result,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ticket details fetched successfully.',
            'data' => $result,
        ]);

    } catch (\Throwable $e) {

        Log::error('eTravos TICKET error', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Unable to fetch ticket details.',
            'error' => $e->getMessage(),
        ], 500);
    }
}
    public function bookingConfirm()
{
    return view('frontend.booking-confirm');
}
public function paymentPage()
{
    return view('frontend.payment');
}
public function ticketPage()
{
    return view('frontend.ticket');
}
/**
 * Create Razorpay Test Mode Order
 */
public function createRazorpayOrder(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'refNo' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Booking reference number is required.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $refNo = (string) $request->refNo;

        /*
         * Make sure this refNo belongs to the booking
         * that was blocked through our application.
         */
        $blockData = session('etravos_block');

        if (empty($blockData)) {
            return response()->json([
                'success' => false,
                'message' => 'No active blocked booking was found. Please start the booking again.',
            ], 400);
        }

        $sessionRefNo = $blockData['refNo'] ?? null;

        if (
            empty($sessionRefNo) ||
            (string) $sessionRefNo !== $refNo
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid booking reference number.',
            ], 400);
        }

        /*
         * Get amount from the request.
         *
         * Your current frontend stores totalAmount in sessionStorage.
         */
        $amount = $request->input('amount');

        if (!$amount) {
            return response()->json([
                'success' => false,
                'message' => 'Booking amount is missing.',
            ], 422);
        }

        $amount = (float) $amount;

        if ($amount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid booking amount.',
            ], 422);
        }

        /*
         * Razorpay expects amount in the smallest currency unit.
         *
         * Example:
         * ₹943.95 = 94395 paise
         */
        $amountInPaise = (int) round($amount * 100);

        $keyId = config('services.razorpay.key_id');
        $keySecret = config('services.razorpay.key_secret');

        if (empty($keyId) || empty($keySecret)) {
            Log::error('Razorpay credentials are missing.');

            return response()->json([
                'success' => false,
                'message' => 'Razorpay configuration is missing.',
            ], 500);
        }

        /*
         * Create Razorpay Order
         */
        $response = Http::withBasicAuth(
            $keyId,
            $keySecret
        )->post('https://api.razorpay.com/v1/orders', [
            'amount' => $amountInPaise,
            'currency' => 'INR',
            'receipt' => 'ETRAVOS_' . $refNo,
            'notes' => [
                'refNo' => $refNo,
                'booking_type' => 'bus',
            ],
        ]);

        if (!$response->successful()) {
            Log::error('Razorpay order creation failed', [
                'status' => $response->status(),
                'response' => $response->json(),
                'refNo' => $refNo,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to create Razorpay order.',
                'error' => $response->json(),
            ], 500);
        }

        $order = $response->json();

        /*
         * Save Razorpay order information in Laravel session.
         * This will be checked again during payment verification.
         */
        session([
            'razorpay_order' => [
                'id' => $order['id'] ?? null,
                'amount' => $amountInPaise,
                'currency' => 'INR',
                'refNo' => $refNo,
            ],
        ]);

        Log::info('Razorpay order created', [
            'order_id' => $order['id'] ?? null,
            'refNo' => $refNo,
            'amount' => $amountInPaise,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Razorpay order created successfully.',
            'order' => [
                'id' => $order['id'],
                'amount' => $order['amount'],
                'currency' => $order['currency'],
            ],
        ]);

    } catch (\Throwable $e) {

        Log::error('Razorpay order creation error', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Unable to create payment order.',
            'error' => $e->getMessage(),
        ], 500);
    }
}
/**
 * Verify Razorpay payment and then BOOK the eTravos ticket
 */
public function verifyRazorpayPayment(Request $request)
{
    try {

        $validator = Validator::make($request->all(), [
            'refNo' => 'required|string',
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Razorpay payment information.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $refNo = (string) $request->refNo;

        /*
         * Get the blocked eTravos booking.
         */
        $blockData = session('etravos_block');

        if (empty($blockData)) {
            return response()->json([
                'success' => false,
                'message' => 'No active blocked booking was found.',
            ], 400);
        }

        $sessionRefNo = $blockData['refNo'] ?? null;

        if (
            empty($sessionRefNo) ||
            (string) $sessionRefNo !== $refNo
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid booking reference number.',
            ], 400);
        }

        /*
         * Get the Razorpay order saved when the order was created.
         */
        $razorpayOrder = session('razorpay_order');

        if (empty($razorpayOrder)) {
            return response()->json([
                'success' => false,
                'message' => 'Razorpay order information was not found.',
            ], 400);
        }

        /*
         * Make sure the Razorpay order belongs to this booking.
         */
        if (
            empty($razorpayOrder['id']) ||
            $razorpayOrder['id'] !== $request->razorpay_order_id
        ) {
            Log::warning('Razorpay order mismatch', [
                'session_order_id' => $razorpayOrder['id'] ?? null,
                'request_order_id' => $request->razorpay_order_id,
                'refNo' => $refNo,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Razorpay order does not match this booking.',
            ], 400);
        }

        /*
         * Prevent accidental duplicate BOOK requests.
         */
        $existingBooking = session('etravos_booking');

        if (!empty($existingBooking['tktNo'])) {

            return response()->json([
                'success' => true,
                'message' => 'Ticket was already booked.',
                'tktNo' => $existingBooking['tktNo'],
                'pnrNo' => $existingBooking['pnrNo'] ?? null,
                'tktKey' => $existingBooking['tktKey'] ?? null,
                'operatorNumber' => $existingBooking['operatorNumber'] ?? null,
            ]);
        }

        $keySecret = config('services.razorpay.key_secret');

        if (empty($keySecret)) {
            Log::error('Razorpay secret is missing.');

            return response()->json([
                'success' => false,
                'message' => 'Razorpay configuration is missing.',
            ], 500);
        }

        /*
         * Razorpay signature:
         *
         * HMAC_SHA256(
         *     razorpay_order_id + "|" + razorpay_payment_id,
         *     razorpay_key_secret
         * )
         */
        $payload =
            $request->razorpay_order_id .
            '|' .
            $request->razorpay_payment_id;

        $generatedSignature = hash_hmac(
            'sha256',
            $payload,
            $keySecret
        );

        /*
         * Compare Razorpay's signature with our generated signature.
         */
        if (!hash_equals(
            $generatedSignature,
            $request->razorpay_signature
        )) {

            Log::warning('Razorpay signature verification failed', [
                'order_id' => $request->razorpay_order_id,
                'payment_id' => $request->razorpay_payment_id,
                'refNo' => $refNo,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed.',
            ], 400);
        }

        /*
         * Payment is now verified.
         *
         * ONLY NOW call eTravos BOOK.
         */
        Log::info('Razorpay payment verified. Starting eTravos BOOK.', [
            'refNo' => $refNo,
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
        ]);

        $result = $this->etravos->bookTicket($refNo);

        Log::info('eTravos BOOK response after successful payment', [
            'refNo' => $refNo,
            'error' => $result['error'] ?? null,
            'tktNo' => $result['tktNo'] ?? null,
            'pnrNo' => $result['pnrNo'] ?? null,
        ]);

        /*
         * Handle service-level error.
         */
        if (
            isset($result['error']) &&
            $result['error'] === true
        ) {
            return response()->json([
                'success' => false,
                'message' => $result['message']
                    ?? 'Payment succeeded, but ticket booking failed.',
                'payment_verified' => true,
                'booking_failed' => true,
            ], 500);
        }

        /*
         * Handle eTravos API error object.
         */
        if (
            isset($result['error']) &&
            is_array($result['error']) &&
            !empty($result['error'])
        ) {
            return response()->json([
                'success' => false,
                'message' => $result['error']['desc']
                    ?? 'Payment succeeded, but ticket booking failed.',
                'payment_verified' => true,
                'booking_failed' => true,
                'data' => $result,
            ], 422);
        }

        $ticketNumber = $result['tktNo'] ?? null;

        if (!$ticketNumber) {

            Log::error('eTravos BOOK succeeded without ticket number', [
                'refNo' => $refNo,
                'response' => $result,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment was successful, but ticket number was not returned.',
                'payment_verified' => true,
                'booking_failed' => true,
            ], 500);
        }

        /*
         * Save successful booking.
         */
        session([
            'etravos_booking' => [
                'refNo' => $refNo,
                'tktNo' => $result['tktNo'] ?? null,
                'pnrNo' => $result['pnrNo'] ?? null,
                'tktKey' => $result['tktKey'] ?? null,
                'operatorNumber' => $result['operatorNumber'] ?? null,
                'response' => $result,
            ],

            'razorpay_payment' => [
                'order_id' => $request->razorpay_order_id,
                'payment_id' => $request->razorpay_payment_id,
                'signature' => $request->razorpay_signature,
                'refNo' => $refNo,
            ],
        ]);

        Log::info('Bus booking completed successfully', [
            'refNo' => $refNo,
            'tktNo' => $result['tktNo'] ?? null,
            'pnrNo' => $result['pnrNo'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment verified and ticket booked successfully.',
            'payment_verified' => true,
            'booking_completed' => true,
            'tktNo' => $result['tktNo'] ?? null,
            'pnrNo' => $result['pnrNo'] ?? null,
            'tktKey' => $result['tktKey'] ?? null,
            'operatorNumber' => $result['operatorNumber'] ?? null,
            'data' => $result,
        ]);

    } catch (\Throwable $e) {

        Log::error('Razorpay payment verification error', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Payment verification failed.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

}


