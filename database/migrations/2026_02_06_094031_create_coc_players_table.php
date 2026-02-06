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
        Schema::create('coc_players', function (Blueprint $table) {
            $table->id();
            $table->string('player_tag')->unique();
            $table->string('player_name');
            $table->integer('town_hall_level');
            $table->json('payload');
            $table->timestamp('last_fetched_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coc_players');
    }
};
