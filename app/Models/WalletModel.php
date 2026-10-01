<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletModel extends Model{
    use HasFactory;
    protected $table = 'user_wallet';
    protected $keyType = 'integer';
    protected $fillable = [
        'transaction_id',
        'user_id',
        'referral_id',
        'booking_id',
        'booking_data',
        'amount',
        'type',
        'message',
        'created_at',
        
    ];
}


