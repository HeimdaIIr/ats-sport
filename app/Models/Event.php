<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'location',
        'department', 'event_date', 'registration_deadline',
        'max_participants', 'status', 'is_featured'
    ];

    protected $casts = [
        'event_date' => 'date',
        'registration_deadline' => 'date',
        'is_featured'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            $event->slug = Str::slug($event->name);
        });
    }
}
