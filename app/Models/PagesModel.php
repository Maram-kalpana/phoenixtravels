<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagesModel extends Model
{
    use HasFactory;
    protected $table = 'information_pages';
    protected $keyType = 'integer';
    protected $fillable = [
    'title',
    'slug',
    'description',
    'meta_title',
    'meta_keywords',
    'meta_description',
    'status',
    'created_at','updated_at'];
}
