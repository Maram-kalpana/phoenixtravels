<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternationalPackageCarPriceModel extends Model
{
    protected $table = 'international_package_car_prices';
    
    protected $fillable = [
        'package_id',
        'star_category',
        'hotel_name',
        'rooms_cost',
        'extra_bed_cost',
        'attract_tickets',
        'honeymoon_cost'
    ];
    
    public function package()
    {
        return $this->belongsTo(InternationalPackageModel::class, 'package_id');
    }
}