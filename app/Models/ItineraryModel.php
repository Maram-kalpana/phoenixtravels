<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItineraryModel extends Model{
    use HasFactory;
    protected $table = 'itinerary';
    protected $keyType = 'integer';
}


