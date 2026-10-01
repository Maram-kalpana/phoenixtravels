<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageCarPriceModel extends Model
{
    protected $table = 'package_car_prices';
    
    protected $fillable = [
        'package_id',
        'pax',
        'car_name',
        'car_per_day',
        'driver_batta'
    ];
    
    public function package()
    {
        return $this->belongsTo(PackageModel::class, 'package_id');
    }
}