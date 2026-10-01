<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackagesImagesModel extends Model{
    use HasFactory;
    protected $table = 'packages_images';
    protected $keyType = 'integer';
}
