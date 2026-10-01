<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartureDate extends Model
{
    use HasFactory;
    
    protected $table = 'departure_dates';
    
    protected $fillable = [
        'fixed_departure_id',
        'departure_date',
        'price',
        'available_seats',
        'status',
    ];
    
    protected $casts = [
        'departure_date' => 'date',
        'price' => 'decimal:2',
        'available_seats' => 'integer',
        'status' => 'integer',
    ];
    
    /**
     * Get the fixed departure that owns this date
     */
    public function fixedDeparture()
    {
        return $this->belongsTo(FixedDepartures::class, 'fixed_departure_id');
    }
}