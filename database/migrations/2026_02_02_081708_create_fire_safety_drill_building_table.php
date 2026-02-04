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
        Schema::create('fire_safety_drill_building', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drill_id')->constrained('fire_safety_evacuation_drills')->onDelete('cascade');
            $table->foreignId('building_id')->constrained('firesafety_buildings')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fire_safety_drill_building');
    }
};
