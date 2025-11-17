<?php

namespace App\Models\ChronoFront;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class RaceTime extends Model
{
    protected $connection = 'chronofront';

    protected $fillable = [
        'entrant_id',
        'timing_point_id',
        'detection_time',
        'detection_type',
        'rfid_tag_read',
    ];

    protected $casts = [
        'detection_time' => 'datetime',
    ];

    /**
     * Get the entrant (participant) that owns this race time
     */
    public function entrant(): BelongsTo
    {
        return $this->belongsTo(Entrant::class);
    }

    /**
     * Get the timing point where this time was recorded
     */
    public function timingPoint(): BelongsTo
    {
        return $this->belongsTo(TimingPoint::class);
    }

    /**
     * Check if this was an RFID detection
     */
    public function isRfidDetection(): bool
    {
        return $this->detection_type === 'rfid';
    }

    /**
     * Check if this was a manual entry
     */
    public function isManualEntry(): bool
    {
        return $this->detection_type === 'manual';
    }

    /**
     * Get formatted detection time
     */
    public function getFormattedTimeAttribute(): string
    {
        return $this->detection_time->format('H:i:s.v');
    }

    /**
     * Scope to get only RFID detections
     */
    public function scopeRfid($query)
    {
        return $query->where('detection_type', 'rfid');
    }

    /**
     * Scope to get only manual entries
     */
    public function scopeManual($query)
    {
        return $query->where('detection_type', 'manual');
    }

    /**
     * Scope to get times ordered by detection time
     */
    public function scopeOrderedByTime($query)
    {
        return $query->orderBy('detection_time');
    }

    /**
     * Get times for a specific timing point
     */
    public function scopeAtPoint($query, $timingPointId)
    {
        return $query->where('timing_point_id', $timingPointId);
    }
}
