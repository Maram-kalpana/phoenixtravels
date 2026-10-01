<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageHighlightsProperty extends Model
{
    protected $table = 'package_highlights_property';
    
    protected $fillable = [
        'highlight_id',
        'image',
        'created_at'
    ];
}