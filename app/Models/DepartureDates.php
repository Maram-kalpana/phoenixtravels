<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartureDates extends Model
{
    use HasFactory;
    
    protected $table = 'departure_dates';
    protected $keyType = 'integer';
   
}