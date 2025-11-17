<?php

namespace App\Events\ChronoFront;

use App\Models\ChronoFront\RaceTime;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Événement déclenché lors de l'enregistrement d'un temps de course (détection RFID ou saisie manuelle)
 * Broadcaste via WebSockets pour mise à jour temps réel des écrans
 */
class RaceTimeRecorded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public RaceTime $raceTime;

    /**
     * Create a new event instance.
     */
    public function __construct(RaceTime $raceTime)
    {
        $this->raceTime = $raceTime->load(['entrant', 'timingPoint']);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Broadcaster sur le canal de la course
        $raceId = $this->raceTime->entrant->race_id;

        return [
            new Channel("race.{$raceId}"),
            new Channel("timing-point.{$this->raceTime->timing_point_id}"),
            new Channel('chronofront.live') // Canal général pour tous les événements
        ];
    }

    /**
     * Nom de l'événement pour le broadcast
     */
    public function broadcastAs(): string
    {
        return 'race-time.recorded';
    }

    /**
     * Données à broadcaster
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'race_time_id' => $this->raceTime->id,
            'entrant' => [
                'id' => $this->raceTime->entrant->id,
                'bib_number' => $this->raceTime->entrant->bib_number,
                'firstname' => $this->raceTime->entrant->firstname,
                'lastname' => $this->raceTime->entrant->lastname,
                'gender' => $this->raceTime->entrant->gender,
                'rfid_tag' => $this->raceTime->entrant->rfid_tag,
            ],
            'timing_point' => [
                'id' => $this->raceTime->timingPoint->id,
                'name' => $this->raceTime->timingPoint->name,
                'point_type' => $this->raceTime->timingPoint->point_type,
                'distance_km' => $this->raceTime->timingPoint->distance_km,
            ],
            'detection_time' => $this->raceTime->detection_time->toIso8601String(),
            'detection_type' => $this->raceTime->detection_type,
            'race_id' => $this->raceTime->entrant->race_id,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
