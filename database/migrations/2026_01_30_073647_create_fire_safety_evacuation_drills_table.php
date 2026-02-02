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
        Schema::create('fire_safety_evacuation_drills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id');
            $table->string('drill_type')->default('scheduled'); // scheduled, unannounced
            $table->date('drill_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('participants_count')->nullable();
            $table->integer('evacuation_time_minutes')->nullable();
            $table->string('status')->default('scheduled'); // scheduled, completed, cancelled
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            $table->foreign('school_id')->references('id')->on('firesafety_school_information')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fire_safety_evacuation_drills');
    }
};
