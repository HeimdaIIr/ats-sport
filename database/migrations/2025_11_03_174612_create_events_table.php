<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('events', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('slug')->unique();
        $table->text('description')->nullable();
        $table->string('location');
        $table->string('department', 10);
        $table->date('event_date');
        $table->date('registration_deadline');
        $table->integer('max_participants')->nullable();
        $table->enum('status', ['upcoming', 'open', 'closed', 'completed'])->default('upcoming');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
