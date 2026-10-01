<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternationalItineraryModel extends Model{
    use HasFactory;
    protected $table = 'international_itinerary';
    protected $keyType = 'integer';
}


