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
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->string('message'); // Agregar columna "message"
            $table->dateTime('scheduled_at'); // Agregar columna "scheduled_at"
            $table->enum('status', ['pending', 'sent'])->default('pending'); // Agregar columna "status"
            $table->string('social_media'); // Agregar columna "social_media"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
