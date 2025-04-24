<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Announcement extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'image',
        'type',
        'is_active',
        'publish_date',
        'expiry_date',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'publish_date' => 'datetime',
        'expiry_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that created the announcement.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope a query to only include active announcements.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include published announcements.
     */
    public function scopePublished($query)
    {
        return $query->where('publish_date', '<=', Carbon::now());
    }

    /**
     * Scope a query to only include non-expired announcements.
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expiry_date')
              ->orWhere('expiry_date', '>=', Carbon::now());
        });
    }

    /**
     * Scope a query to only include currently active announcements (published, not expired, and active).
     */
    public function scopeCurrent($query)
    {
        return $query->active()->published()->notExpired();
    }

    /**
     * Scope a query to filter by type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get the formatted publish date.
     */
    public function getFormattedPublishDateAttribute()
    {
        return $this->publish_date->format('M d, Y');
    }

    /**
     * Get the formatted expiry date.
     */
    public function getFormattedExpiryDateAttribute()
    {
        return $this->expiry_date ? $this->expiry_date->format('M d, Y') : 'No Expiry';
    }

    /**
     * Check if the announcement is currently active (published, not expired, and active).
     */
    public function getIsCurrentAttribute()
    {
        return $this->is_active && 
               $this->publish_date->isPast() && 
               (!$this->expiry_date || $this->expiry_date->isFuture());
    }

    /**
     * Get a summary of the content.
     */
    public function getSummaryAttribute()
    {
        return strlen($this->content) > 150 ? substr($this->content, 0, 150) . '...' : $this->content;
    }

    /**
     * Get the subject string for activity logging.
     *
     * @return string
     */
    protected function getActivitySubject(): string
    {
        return $this->title;
    }
}
