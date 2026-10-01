<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternationalSubmitPayModel extends Model{
    use HasFactory;
    protected $table = 'international_pay_submit';
    protected $keyType = 'integer';
    
}
