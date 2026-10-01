<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'booking_number',
        'fixed_departure_id',
        'travel_date',
        'seat_numbers',
        'total_seats',
        'total_amount',
        'price_per_seat',
        'name',
        'email',
        'phone',
        'status',
        'razorpay_order_id',
        'payment_id',
        'payment_status',
        'payment_method',
        'payment_date',
        'cancellation_reason',
        'cancelled_at',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'travel_date' => 'date',
        'total_amount' => 'decimal:2',
        'price_per_seat' => 'decimal:2',
        'payment_date' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate booking number before creating
        static::creating(function ($booking) {
            if (empty($booking->booking_number)) {
                $booking->booking_number = self::generateBookingNumber();
            }
        });
    }

    /**
     * Generate a unique booking number
     */
    public static function generateBookingNumber()
    {
        $prefix = 'BK';
        $date = date('Ymd');
        $random = strtoupper(substr(uniqid(), -6));
        $bookingNumber = $prefix . $date . $random;

        // Ensure uniqueness
        while (self::where('booking_number', $bookingNumber)->exists()) {
            $random = strtoupper(substr(uniqid(), -6));
            $bookingNumber = $prefix . $date . $random;
        }

        return $bookingNumber;
    }

    /**
     * Relationship with Fixed Departure
     */
    public function fixedDeparture()
    {
        return $this->belongsTo(FixedDepartures::class, 'fixed_departure_id');
    }

    /**
     * Scope: Confirmed bookings
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope: Pending bookings
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Get booked seats for a specific date and departure
     */
    public static function getBookedSeats($departureId, $date)
    {
        $bookings = self::where('fixed_departure_id', $departureId)
            ->where('travel_date', $date)
            ->where('status', '!=', 'cancelled')
            ->get();

        $bookedSeats = [];
        foreach ($bookings as $booking) {
            $seats = $booking->getSeatNumbersArray();
            $bookedSeats = array_merge($bookedSeats, $seats);
        }

        return array_unique($bookedSeats);
    }

    /**
     * Get seat numbers as array
     */
    public function getSeatNumbersArray()
    {
        if (empty($this->seat_numbers)) {
            return [];
        }
        
        return array_map('intval', explode(',', $this->seat_numbers));
    }

    /**
     * Check if payment is completed
     */
    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Check if booking can be cancelled
     */
    public function canBeCancelled()
    {
        $allowedStatuses = ['pending', 'confirmed'];
        return in_array($this->status, $allowedStatuses) && 
               $this->travel_date->isFuture();
    }
}