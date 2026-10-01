<?php
// app/Http/Requests/SchoolExcursionRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SchoolExcursionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // Contact Information
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'address' => 'required|string',
            
            // Tour Information
            'starting_place' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'number_of_people' => 'required|integer|min:1',
            'preferred_date' => 'required|date|after_or_equal:today',
            
            // Company Information
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string',
            'company_phone_1' => 'required|string|max:20',
            
            // Authorized Person
            'authorized_name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'authorized_contact_1' => 'required|string|max:20',
        ];
    }

    public function messages()
    {
        return [
            'full_name.required' => 'Please enter your full name',
            'email.required' => 'Please enter your email address',
            'email.email' => 'Please enter a valid email address',
            'phone.required' => 'Please enter your phone number',
            'preferred_date.after_or_equal' => 'Preferred date must be today or a future date',
        ];
    }
}