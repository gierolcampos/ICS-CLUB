<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'content',
        'type',
        'status',
        'recipient',
        'sender_id',
        'date',
        'file_path',
    ];
    
    /**
     * Get the user who sent this letter.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
} 