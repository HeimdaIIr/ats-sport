<?php

namespace App\Models\ChronoFront;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TimingPoint extends Model
{
    protected $connection = 'chronofront';

    protected $fillable = [
        'race_id',
        'name',
        'distance_km',
        'point_type',
        'order_number',
    ];

    protected $casts = [
        'distance_km' => 'decimal:2',
        'order_number' => 'integer',
    ];

    /**
     * Get the race that owns the timing point
     */
    public function race(): BelongsTo
    {
        return $this->belongsTo(Race::class);
    }

    /**
     * Get all race times recorded at this timing point
     */
    public function raceTimes(): HasMany
    {
        return $this->hasMany(RaceTime::class);
    }

    /**
     * Check if this is the start point
     */
    public function isStart(): bool
    {
        return $this->point_type === 'start';
    }

    /**
     * Check if this is the finish point
     */
    public function isFinish(): bool
    {
        return $this->point_type === 'finish';
    }

    /**
     * Check if this is an intermediate point
     */
    public function isIntermediate(): bool
    {
        return $this->point_type === 'intermediate';
    }

    /**
     * Get timing points ordered by their order number
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order_number');
    }
}
