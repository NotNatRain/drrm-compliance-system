<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incident_schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('district')->nullable();
            $table->string('division')->nullable();
            $table->string('region')->nullable();
            $table->string('school_id')->nullable();
            $table->integer('incident_count')->default(0);
            $table->date('last_incident_date')->nullable();
            $table->timestamps();
            
            $table->unique(['name', 'district']);
        });

        // Seed with common schools
        DB::table('incident_schools')->insert([
            ['name' => 'North Central High School', 'district' => 'Central District'],
            ['name' => 'South Elementary School', 'district' => 'South District'],
            ['name' => 'East National High School', 'district' => 'East District'],
            ['name' => 'West Integrated School', 'district' => 'West District'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_schools');
    }
};