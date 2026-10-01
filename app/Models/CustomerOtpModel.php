<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerOtpModel extends Model
{
    use HasFactory;
    protected $table = 'customer_otp';
    protected $keyType = 'integer';
    protected $fillable = ['customer_id', 'otp', 'expire_at'];
}
