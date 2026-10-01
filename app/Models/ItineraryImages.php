<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItineraryImages extends Model{
    
    use HasFactory;
    protected $table = 'itinerary_images';
    protected $keyType = 'integer';
    public $timestamps = false;

}
