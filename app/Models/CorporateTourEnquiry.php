<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorporateTourEnquiry extends Model
{
    use HasFactory;

    protected $table = 'corporate_tour_enquiries';

    protected $fillable = [
        // Contact Information
        'full_name', 'email', 'phone', 'country_id', 'state_id', 'city_id', 
        'address', 'apartment_number',
        
        // Tour Information
        'starting_place', 'destination', 'number_of_people', 'number_of_buses',
        'bus_type', 'train_class', 'flight_class', 'preferred_date',
        'pickup_point', 'dropping_point', 'sightseeing',
        
        // Participants
        'adults', 'children', 'infants',
        
        // Hotel Information
        'hotel_category', 'hotel_location', 'number_of_rooms', 'sharing_type',
        
        // Conference Hall
        'conference_hall', 'seating_capacity', 'stage_type', 'seating_arrangement',
        'equipment',
        
        // Food Menu
        'breakfast_menu', 'lunch_menu', 'dinner_menu', 'morning_snacks_menu',
        'evening_snacks_menu', 'starters_menu',
        
        // Night Party
        'night_party', 'drinks_type', 'drinks_bottles',
        
        // Payment Information
        'payment_method', 'bank_name', 'bank_branch', 'account_number', 'ifsc_code',
        
        // Terms & Policies
        'payment_terms', 'cancellation_policy', 'refund_policy',
        
        // Company Information
        'company_name', 'company_address', 'company_phone_1', 'company_phone_2',
        
        // Authorized Person
        'authorized_name', 'designation', 'authorized_contact_1', 'authorized_contact_2',
        
        // Payment Authority
        'payment_authority_name', 'payment_authority_designation',
        'payment_authority_contact_1', 'payment_authority_contact_2',
        
        // Additional Info
        'additional_info',
        
        'status'
    ];

    protected $casts = [
        'equipment' => 'array',
        'breakfast_menu' => 'array',
        'lunch_menu' => 'array',
        'dinner_menu' => 'array',
        'morning_snacks_menu' => 'array',
        'evening_snacks_menu' => 'array',
        'starters_menu' => 'array',
        'drinks_bottles' => 'array',
        'preferred_date' => 'date',
    ];

    // Relationships
    public function country()
    {
        return $this->belongsTo(CountryModel::class, 'country_id');
    }

    public function state()
    {
        return $this->belongsTo(StateModel::class, 'state_id');
    }

    public function city()
    {
        return $this->belongsTo(CityModel::class, 'city_id');
    }
}