<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incident_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color_class');
            $table->string('description')->nullable();
            $table->integer('priority')->default(0);
            $table->timestamps();
        });

        // Seed default incident types
        DB::table('incident_types')->insert([
            ['name' => 'Tropical Cyclone', 'color_class' => 'type-cyclone', 'priority' => 1],
            ['name' => 'Heavy Rainfall', 'color_class' => 'type-rainfall', 'priority' => 2],
            ['name' => 'Earthquake', 'color_class' => 'type-earthquake', 'priority' => 1],
            ['name' => 'Landslide', 'color_class' => 'type-landslide', 'priority' => 1],
            ['name' => 'Flooding', 'color_class' => 'type-flooding', 'priority' => 2],
            ['name' => 'Fire', 'color_class' => 'type-fire', 'priority' => 1],
            ['name' => 'Accidents', 'color_class' => 'type-accident', 'priority' => 3],
            ['name' => 'Violence/Conflict', 'color_class' => 'type-violence', 'priority' => 1],
            ['name' => 'Others', 'color_class' => 'type-others', 'priority' => 4],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_types');
    }
};