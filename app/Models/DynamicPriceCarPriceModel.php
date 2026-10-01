<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DynamicPriceCarPriceModel extends Model{
    
    protected $table = 'dynamic_car_prices';
    
    protected $fillable = [
        'state_id',
        'pax',
        'car_name',
        'car_per_day',
        'driver_batta'
    ];
    
   
}