<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageHighlightsSightseeing extends Model
{
    protected $table = 'package_highlights_sightseeing';
    
    protected $fillable = [
        'highlight_id',
        'image',
        'created_at'
    ];
}