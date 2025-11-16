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
        Schema::connection('chronofront')->create('timing_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('race_id')->constrained()->onDelete('cascade');
            $table->string('name', 100); // Ex: "Départ", "Arrivée", "Km 5"
            $table->decimal('distance_km', 8, 2)->default(0); // Distance depuis le départ
            $table->enum('point_type', ['start', 'intermediate', 'finish'])->default('intermediate');
            $table->integer('order_number')->default(1); // Ordre des points (1=départ, 2=km5, 3=arrivée)
            $table->timestamps();

            // Index pour requêtes fréquentes
            $table->index(['race_id', 'order_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('chronofront')->dropIfExists('timing_points');
    }
};
