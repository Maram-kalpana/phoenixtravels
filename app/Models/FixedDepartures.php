<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FixedDepartures extends Model
{
    use HasFactory;
    protected $table = 'fixed_departures';
    protected $keyType = 'integer';
    
}
