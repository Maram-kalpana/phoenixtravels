<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_intent_id',
        'user_id',
        'amount',
        'currency',
        'status',
        'payment_method_details',
        'receipt_url',
    ];

    protected $casts = [
        'payment_method_details' => 'array',
    ];
}