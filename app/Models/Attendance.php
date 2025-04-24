<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'event_id',
        'member_id',
        'status',
        'has_attended',
        'feedback',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'has_attended' => 'boolean',
    ];

    /**
     * Get the event that the attendance belongs to.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the member that the attendance belongs to.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Scope a query to only include confirmed attendances.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'Going');
    }

    /**
     * Scope a query to only include attended members.
     */
    public function scopeAttended($query)
    {
        return $query->where('has_attended', true);
    }
}
