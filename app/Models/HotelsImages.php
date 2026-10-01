<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelsImages extends Model{
    use HasFactory;
    protected $table = 'hotels_images';
    protected $keyType = 'integer';
}
