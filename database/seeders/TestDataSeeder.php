<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChronoFront\Event;
use App\Models\ChronoFront\Race;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    /**
     * Créer des données de test pour ChronoFront
     */
    public function run(): void
    {
        // Créer un événement de test
        $event = Event::create([
            'name' => 'Semi-Marathon de Sète 2025',
            'date_start' => Carbon::now()->addDays(30),
            'date_end' => Carbon::now()->addDays(30),
            'location' => 'Sète, France',
            'description' => 'Événement de test pour ChronoFront',
            'is_active' => true
        ]);

        // Créer quelques courses
        $races = [
            [
                'name' => '10 km',
                'type' => '1_passage',
                'distance' => 10.0,
                'laps' => 1,
                'best_time' => false,
                'description' => 'Course de 10 kilomètres'
            ],
            [
                'name' => 'Semi-Marathon',
                'type' => '1_passage',
                'distance' => 21.1,
                'laps' => 1,
                'best_time' => false,
                'description' => 'Semi-marathon 21,1 km'
            ],
            [
                'name' => 'Trail 15 km',
                'type' => '1_passage',
                'distance' => 15.0,
                'laps' => 1,
                'best_time' => false,
                'description' => 'Trail de 15 kilomètres'
            ]
        ];

        foreach ($races as $raceData) {
            Race::create(array_merge($raceData, ['event_id' => $event->id]));
        }

        $this->command->info('✅ Données de test créées avec succès!');
        $this->command->info("   - 1 événement: {$event->name}");
        $this->command->info("   - 3 courses: 10km, Semi-Marathon, Trail 15km");
    }
}
