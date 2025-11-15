<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChronoFront\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Catégories FFA (Fédération Française d'Athlétisme) 2025
     */
    public function run(): void
    {
        $categories = [
            // Catégories Hommes
            [
                'name' => 'SEM - Senior Homme',
                'gender' => 'M',
                'age_min' => 20,
                'age_max' => 39,
                'color' => '#3B82F6'
            ],
            [
                'name' => 'V1M - Vétéran 1 Homme',
                'gender' => 'M',
                'age_min' => 40,
                'age_max' => 49,
                'color' => '#10B981'
            ],
            [
                'name' => 'V2M - Vétéran 2 Homme',
                'gender' => 'M',
                'age_min' => 50,
                'age_max' => 59,
                'color' => '#8B5CF6'
            ],
            [
                'name' => 'V3M - Vétéran 3 Homme',
                'gender' => 'M',
                'age_min' => 60,
                'age_max' => 69,
                'color' => '#F59E0B'
            ],
            [
                'name' => 'V4M - Vétéran 4 Homme',
                'gender' => 'M',
                'age_min' => 70,
                'age_max' => 120,
                'color' => '#EF4444'
            ],
            [
                'name' => 'ESM - Espoir Homme',
                'gender' => 'M',
                'age_min' => 18,
                'age_max' => 19,
                'color' => '#06B6D4'
            ],
            [
                'name' => 'CAM - Cadet Homme',
                'gender' => 'M',
                'age_min' => 16,
                'age_max' => 17,
                'color' => '#14B8A6'
            ],

            // Catégories Femmes
            [
                'name' => 'SEF - Senior Femme',
                'gender' => 'F',
                'age_min' => 20,
                'age_max' => 39,
                'color' => '#EC4899'
            ],
            [
                'name' => 'V1F - Vétéran 1 Femme',
                'gender' => 'F',
                'age_min' => 40,
                'age_max' => 49,
                'color' => '#F59E0B'
            ],
            [
                'name' => 'V2F - Vétéran 2 Femme',
                'gender' => 'F',
                'age_min' => 50,
                'age_max' => 59,
                'color' => '#EF4444'
            ],
            [
                'name' => 'V3F - Vétéran 3 Femme',
                'gender' => 'F',
                'age_min' => 60,
                'age_max' => 69,
                'color' => '#A855F7'
            ],
            [
                'name' => 'V4F - Vétéran 4 Femme',
                'gender' => 'F',
                'age_min' => 70,
                'age_max' => 120,
                'color' => '#DC2626'
            ],
            [
                'name' => 'ESF - Espoir Femme',
                'gender' => 'F',
                'age_min' => 18,
                'age_max' => 19,
                'color' => '#06B6D4'
            ],
            [
                'name' => 'CAF - Cadet Femme',
                'gender' => 'F',
                'age_min' => 16,
                'age_max' => 17,
                'color' => '#14B8A6'
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }

        $this->command->info('✅ 14 catégories FFA créées avec succès!');
    }
}
