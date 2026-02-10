<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hero_equipments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('rarity'); // Common, Epic
            $table->string('rank')->nullable(); // SSS, SS, S, A, etc.
            $table->text('reason')->nullable();
            $table->string('image_path')->nullable();
            $table->string('color')->nullable(); // For UI badges
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_equipments');
    }
};
