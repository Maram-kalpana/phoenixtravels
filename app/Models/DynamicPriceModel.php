<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicPriceModel extends Model
{
    use HasFactory;
    protected $table = 'dynamic_price';
    protected $keyType = 'integer';
    
}
