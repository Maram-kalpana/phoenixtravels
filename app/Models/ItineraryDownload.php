<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class ItineraryDownload extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'tour_id',
        'ip_address',
        'user_agent',
        'downloaded_at',
        'otp_verified_at',
        'pdf_generated_at',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'downloaded_at' => 'datetime',
        'otp_verified_at' => 'datetime',
        'pdf_generated_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => 'pending'
    ];

    /**
     * Get the tour that owns the download record.
     */
    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    /**
     * Scope a query to only include completed downloads.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include pending downloads.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include failed downloads.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Mark the download as completed.
     */
    public function markAsCompleted(): bool
    {
        return $this->update([
            'status' => 'completed',
            'downloaded_at' => now()
        ]);
    }

    /**
     * Mark the download as failed.
     */
    public function markAsFailed(): bool
    {
        return $this->update([
            'status' => 'failed'
        ]);
    }

    /**
     * Check if this is a duplicate download.
     */
    public function isDuplicate(): bool
    {
        return static::where('email', $this->email)
                     ->where('tour_id', $this->tour_id)
                     ->where('status', 'completed')
                     ->where('downloaded_at', '>', now()->subHours(24))
                     ->exists();
    }

    /**
     * Get download statistics for a tour.
     */
    public static function getTourStats($tourId): array
    {
        return [
            'total' => static::where('tour_id', $tourId)->count(),
            'today' => static::where('tour_id', $tourId)->today()->count(),
            'this_week' => static::where('tour_id', $tourId)->thisWeek()->count(),
            'this_month' => static::where('tour_id', $tourId)->thisMonth()->count(),
            'unique_emails' => static::where('tour_id', $tourId)->distinct('email')->count(),
        ];
    }

    /**
     * Get top downloaded tours.
     */
    public static function getTopDownloaded($limit = 10)
    {
        return static::select('tour_id')
                     ->selectRaw('COUNT(*) as download_count')
                     ->selectRaw('COUNT(DISTINCT email) as unique_downloaders')
                     ->groupBy('tour_id')
                     ->orderByDesc('download_count')
                     ->limit($limit)
                     ->with('tour')
                     ->get();
    }

    /**
     * Get download trends.
     */
    public static function getDownloadTrends($days = 30)
    {
        return static::selectRaw('DATE(downloaded_at) as date')
                     ->selectRaw('COUNT(*) as count')
                     ->whereDate('downloaded_at', '>=', now()->subDays($days))
                     ->groupBy('date')
                     ->orderBy('date')
                     ->get();
    }
}