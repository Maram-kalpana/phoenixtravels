<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerModel extends Model
{
    use HasFactory;
    protected $table = 'banner';
    protected $keyType = 'integer';
    protected $fillable = [
    'course_id',
    'image',
    'status',
    'created_at','updated_at'];
}
