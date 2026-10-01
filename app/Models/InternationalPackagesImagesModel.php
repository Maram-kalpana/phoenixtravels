<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternationalPackagesImagesModel extends Model{
    use HasFactory;
    protected $table = 'international_packages_images';
    protected $keyType = 'integer';
}
