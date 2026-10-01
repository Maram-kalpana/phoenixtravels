<?php
return [
    'api_url' => env('TBO_API_URL', 'https://api-stage.tboair.com'),
    'client_id' => env('TBO_CLIENT_ID'),
    'token_agency_id' => env('TBO_TOKEN_AGENCY_ID'),
    'token_member_id' => env('TBO_TOKEN_MEMBER_ID'),
    'timeout' => env('TBO_TIMEOUT', 30),
    'retry_times' => env('TBO_RETRY_TIMES', 3),
];