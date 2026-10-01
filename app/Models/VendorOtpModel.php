<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorOtpModel extends Model{
    
    use HasFactory;
    protected $table = 'vendor_otp';
    protected $keyType = 'integer';
   
}
