<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageHighlights extends Model
{
    protected $table = 'package_highlights';
    
    protected $fillable = [
        'package_id',
        'short_description',
        'highlights_image',
        'youtube_link',
        'status',
        'created_at',
        'updated_at'
    ];
    
    // Relationship with gallery images
    public function gallery()
    {
        return $this->hasMany(PackageHighlightsGallery::class, 'highlight_id');
    }
    
    // Relationship with sightseeing images
    public function sightseeing()
    {
        return $this->hasMany(PackageHighlightsSightseeing::class, 'highlight_id');
    }
    
    // Relationship with property photos
    public function propertyPhotos()
    {
        return $this->hasMany(PackageHighlightsProperty::class, 'highlight_id');
    }
}