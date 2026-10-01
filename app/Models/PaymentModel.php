<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentModel extends Model
{
    use HasFactory;
    protected $table = 'payment_settings';
    protected $keyType = 'integer';
    protected $fillable = [
    'razorpay_key_live',
    'razorpay_key_secret_live',
    'razorpay_key_test',
    'razorpay_key_secret_test',
    'mode_type',
    'created_at','updated_at'];
}
