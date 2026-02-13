<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pie_pra_recommendations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scenario_id');
            $table->unsignedBigInteger('school_id');
            $table->boolean('activate_as_evac_center')->default(false);
            $table->unsignedTinyInteger('priority_score')->default(0);
            $table->timestamp('recommended_suspend_classes_at')->nullable();
            $table->timestamp('recommended_start_evac_at')->nullable();
            $table->json('preposition_resources')->nullable();
            $table->text('academic_continuity_notes')->nullable();
            $table->timestamps();

            $table->foreign('scenario_id')->references('id')->on('pie_pra_scenarios')->onDelete('cascade');
            $table->foreign('school_id')->references('id')->on('firesafety_school_information')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pie_pra_recommendations');
    }
};

