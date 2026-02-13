<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pie_pra_volunteer_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scenario_id');
            $table->unsignedBigInteger('volunteer_id');
            $table->unsignedBigInteger('school_id');
            $table->string('role')->nullable();
            $table->string('status')->default('planned'); // planned|active|completed|cancelled
            $table->timestamp('check_in_at')->nullable();
            $table->timestamp('check_out_at')->nullable();
            $table->timestamp('certificate_issued_at')->nullable();
            $table->string('certificate_number')->nullable();
            $table->timestamps();

            $table->foreign('scenario_id')->references('id')->on('pie_pra_scenarios')->onDelete('cascade');
            $table->foreign('volunteer_id')->references('id')->on('pie_pra_volunteers')->onDelete('cascade');
            $table->foreign('school_id')->references('id')->on('firesafety_school_information')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pie_pra_volunteer_assignments');
    }
};

