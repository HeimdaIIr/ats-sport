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
        Schema::connection('chronofront')->create('race_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entrant_id')->constrained()->onDelete('cascade');
            $table->foreignId('timing_point_id')->constrained()->onDelete('cascade');
            $table->timestamp('detection_time'); // Timestamp précis de la détection
            $table->enum('detection_type', ['rfid', 'manual'])->default('rfid');
            $table->string('rfid_tag_read', 50)->nullable(); // Tag RFID lu (pour traçabilité)
            $table->timestamps(); // created_at = moment de l'enregistrement dans la DB

            // Index critiques pour performance
            $table->index(['entrant_id', 'timing_point_id']);
            $table->index('detection_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('chronofront')->dropIfExists('race_times');
    }
};
