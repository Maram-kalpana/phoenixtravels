<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoneymoonItineraryModel extends Model{
    use HasFactory;
    protected $table = 'honeymoon_itinerary';
    protected $keyType = 'integer';
}


