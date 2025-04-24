<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'location',
        'max_participants',
        'status',
        'image',
        'organizer_id',
        'is_featured',
        'category',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_featured' => 'boolean',
    ];

    /**
     * Get the organizer (member) that organized the event.
     */
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'organizer_id');
    }

    /**
     * Get the attendances for the event.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get attendees (members) who are attending this event.
     */
    public function attendees()
    {
        return $this->belongsToMany(Member::class, 'attendances')
            ->withPivot('status', 'has_attended', 'feedback')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include upcoming events.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', Carbon::now())
                     ->orderBy('start_date', 'asc');
    }

    /**
     * Scope a query to only include past events.
     */
    public function scopePast($query)
    {
        return $query->where('end_date', '<', Carbon::now())
                     ->orderBy('start_date', 'desc');
    }

    /**
     * Scope a query to only include ongoing events.
     */
    public function scopeOngoing($query)
    {
        $now = Carbon::now();
        return $query->where('start_date', '<=', $now)
                     ->where('end_date', '>=', $now)
                     ->orderBy('end_date', 'asc');
    }

    /**
     * Scope a query to only include featured events.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get the formatted start date.
     */
    public function getFormattedStartDateAttribute()
    {
        return $this->start_date->format('M d, Y h:i A');
    }

    /**
     * Get the formatted end date.
     */
    public function getFormattedEndDateAttribute()
    {
        return $this->end_date->format('M d, Y h:i A');
    }

    /**
     * Get the event duration in hours.
     */
    public function getDurationAttribute()
    {
        return $this->start_date->diffInHours($this->end_date);
    }

    /**
     * Check if the event is upcoming.
     */
    public function getIsUpcomingAttribute()
    {
        return $this->start_date->isFuture();
    }

    /**
     * Check if the event is ongoing.
     */
    public function getIsOngoingAttribute()
    {
        $now = Carbon::now();
        return $this->start_date->lte($now) && $this->end_date->gte($now);
    }

    /**
     * Check if the event is completed.
     */
    public function getIsCompletedAttribute()
    {
        return $this->end_date->isPast();
    }

    /**
     * Get the count of confirmed attendees.
     */
    public function getConfirmedAttendeesCountAttribute()
    {
        return $this->attendances()->where('status', 'Going')->count();
    }

    /**
     * Check if the event has reached its maximum capacity.
     */
    public function getIsFullAttribute()
    {
        if (!$this->max_participants) {
            return false;
        }
        
        return $this->confirmed_attendees_count >= $this->max_participants;
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
