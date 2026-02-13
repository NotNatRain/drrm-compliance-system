<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('typ_fld_evacuation_centers', function (Blueprint $table) {
            $table->id();

            // Link to existing school information record
            $table->unsignedBigInteger('school_id')->unique();

            $table->string('identification')->nullable(); // school's identification
            $table->string('location')->nullable();       // readable location
            $table->unsignedInteger('capacity')->default(0);

            // quick status fields shown in choose-school
            $table->string('operational_status')->default('operational'); // operational|partial|closed
            $table->string('needs_summary')->nullable(); // quick badge summary on dashboard
            $table->string('occupancy_safety')->default('safe'); // safe|warning|critical
            $table->string('usage_status')->default('cleared'); // occupied|full|cleared
            $table->text('emergency_resources')->nullable(); // description of resources
            $table->string('emergency_resources_usage_status')->nullable(); // e.g. Low/Moderate/High
            $table->string('monitoring_status')->nullable(); // short label
            $table->string('reports_status')->nullable(); // summary of monitoring & reports

            $table->timestamps();

            $table->foreign('school_id')
                ->references('id')
                ->on('firesafety_school_information')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('typ_fld_evacuation_centers');
    }
};

