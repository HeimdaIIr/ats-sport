<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('chronofront')->table('entrants', function (Blueprint $table) {
            // Champs manquants du CSV
            $table->string('license_number', 50)->nullable()->after('club');
            $table->string('address', 255)->nullable()->after('license_number');
            $table->string('postal_code', 20)->nullable()->after('address');
            $table->string('city', 100)->nullable()->after('postal_code');
            $table->string('country', 100)->nullable()->default('France')->after('city');

            // Index pour performances (UNIQUE sur rfid_tag et race_id+bib_number)
            $table->unique('rfid_tag', 'entrants_rfid_tag_unique');
            $table->unique(['race_id', 'bib_number'], 'entrants_race_bib_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('chronofront')->table('entrants', function (Blueprint $table) {
            $table->dropUnique('entrants_rfid_tag_unique');
            $table->dropUnique('entrants_race_bib_unique');

            $table->dropColumn([
                'license_number',
                'address',
                'postal_code',
                'city',
                'country'
            ]);
        });
    }
};
