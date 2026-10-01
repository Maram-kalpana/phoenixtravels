<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoneymoonPackageModel extends Model
{
    use HasFactory;
    protected $table = 'honeymoon_packages';
    protected $keyType = 'integer';
    
    // ADD THIS RELATIONSHIP METHOD
    public function tour()
    {
        return $this->belongsTo(ToursModel::class, 'tour_id', 'id');
    }
    
    public function scopeDomestic($query)
    {
        return $query->whereHas('tour', function($q) {
            $q->where('tour_category_id', 1);
        });
    }
    
    public function scopeInternational($query)
    {
        return $query->whereHas('tour', function($q) {
            $q->whereIn('tour_category_id', [2,3,4]);
        });
    }
    
    public function carPrices(){
        return $this->hasMany(PackageCarPriceModel::class, 'package_id', 'id');
    }
    
    public function roomPrices(){
        return $this->hasMany(PackageRoomPriceModel::class, 'package_id', 'id');
    }
}