<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponsHistoryModel extends Model{
    use HasFactory;
    protected $table = 'coupons_history';
    protected $keyType = 'integer';
    public $timestamps = false;
    
     protected $fillable = [
        'user_id',
        'coupon_id',
        'coupon_code',
        'payment_id',
        'created_at',
        'updated_at',
        'status',
    ];
    
}
