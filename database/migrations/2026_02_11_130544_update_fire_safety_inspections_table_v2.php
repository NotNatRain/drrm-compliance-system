<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // User requested to replace the entire table structure and data
        Schema::dropIfExists('fire_safety_inspections');
        
        Schema::create('fire_safety_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('firesafety_school_information')->onDelete('cascade');
            $table->string('drill_type'); // Earthquake, Fire, Both
            $table->date('inspection_date');
            $table->time('inspection_time');
            $table->time('time_started')->nullable();
            $table->time('time_finished')->nullable();
            $table->string('elapsed_time')->nullable();
            $table->integer('no_of_exits')->nullable();
            $table->integer('no_of_buildings')->nullable();
            $table->integer('no_of_students')->nullable();
            $table->integer('no_of_personnel')->nullable();
            $table->string('monitored_by')->nullable();
            $table->json('checklist_data')->nullable(); // JSON array of checked item IDs/Names
            $table->json('observers_data')->nullable(); // JSON array of checked observer types
            $table->text('remarks')->nullable();
            $table->string('coordinator_name')->nullable(); // School DRRM Coordinator
            $table->string('school_head_name')->nullable(); // School Head
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('fire_safety_inspections');
        
        // Recreate the old table if needed (minimal version)
        Schema::create('fire_safety_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('firesafety_school_information');
            $table->foreignId('building_id')->constrained('firesafety_buildings');
            $table->date('inspection_date');
            $table->string('inspection_type');
            $table->string('inspector');
            $table->text('notes')->nullable();
            $table->enum('status', ['scheduled', 'pending', 'completed', 'overdue', 'cancelled'])->default('scheduled');
            $table->timestamps();
        });
    }
};
