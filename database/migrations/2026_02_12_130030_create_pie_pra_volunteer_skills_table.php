<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pie_pra_volunteer_skills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->nullable();
            $table->timestamps();
        });

        Schema::create('pie_pra_volunteer_skill_pivot', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('volunteer_id');
            $table->unsignedBigInteger('skill_id');
            $table->timestamps();

            $table->foreign('volunteer_id')->references('id')->on('pie_pra_volunteers')->onDelete('cascade');
            $table->foreign('skill_id')->references('id')->on('pie_pra_volunteer_skills')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pie_pra_volunteer_skill_pivot');
        Schema::dropIfExists('pie_pra_volunteer_skills');
    }
};

