<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageHighlightsGallery extends Model
{
    protected $table = 'package_highlights_gallery';
    
    protected $fillable = [
        'highlight_id',
        'image',
        'created_at'
    ];
}