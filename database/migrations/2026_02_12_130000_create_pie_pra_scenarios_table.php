<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pie_pra_scenarios', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('hazard_type');
            $table->unsignedInteger('lead_time_hours')->default(0);
            $table->string('status')->default('draft');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pie_pra_scenarios');
    }
};

