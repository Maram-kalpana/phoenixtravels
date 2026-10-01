<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmitPayModel extends Model{
    use HasFactory;
    protected $table = 'pay_submit';
    protected $keyType = 'integer';
    
}
