<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Member extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'full_name',
        'email',
        'phone',
        'student_id',
        'course',
        'year_level',
        'contact_number',
        'date_of_birth',
        'address',
        'membership_status',
        'membership_date',
        'membership_expiry',
        'date_joined',
        'skills',
        'interests',
        'profile_photo',
        'role',
        'bio',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'membership_date' => 'date',
        'membership_expiry' => 'date',
        'date_joined' => 'date',
    ];

    /**
     * Get the user associated with the member.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the events organized by this member.
     */
    public function organizedEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'organizer_id');
    }

    /**
     * Get the attendances for this member.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the events that the member is attending.
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'attendances')
            ->withPivot('status', 'has_attended', 'feedback')
            ->withTimestamps();
    }

    /**
     * Get the member's confirmed events (where status is 'Going').
     */
    public function confirmedEvents(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'attendances')
            ->wherePivot('status', 'Going')
            ->withPivot('status', 'has_attended', 'feedback')
            ->withTimestamps();
    }

    /**
     * Get the member's full name.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        if ($this->attributes['full_name'] ?? null) {
            return $this->attributes['full_name'];
        }
        
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get the subject string for activity logging.
     *
     * @return string
     */
    protected function getActivitySubject(): string
    {
        return $this->getFullNameAttribute();
    }
}
