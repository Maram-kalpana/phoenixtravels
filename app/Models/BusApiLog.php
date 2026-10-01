<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusApiLog extends Model
{
    protected $table = 'bus_api_logs';
    protected $keyType = 'integer';
    protected $fillable = [
        'endpoint',
        'method',
        'request_data',
        'response_data',
        'status_code',
        'trace_id',
        'ip_address',
        'error_message',
        'response_time'
    ];

    protected $casts = [
        'request_data' => 'array',
        'response_data' => 'array'
    ];
}