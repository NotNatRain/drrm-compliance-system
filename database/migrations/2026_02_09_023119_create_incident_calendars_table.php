<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incident_calendars', function (Blueprint $table) {
            $table->id();
            $table->date('incident_date');
            $table->unsignedBigInteger('school_id')->nullable();
            $table->string('school_name');
            $table->enum('entry_type', ['incident', 'compliance']);
            $table->unsignedBigInteger('incident_type_id')->nullable();
            $table->unsignedBigInteger('incident_status_id')->nullable();
            $table->text('remarks');
            $table->string('reported_by')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->string('verified_by')->nullable();
            $table->integer('affected_personnel')->default(0);
            $table->integer('affected_students')->default(0);
            $table->json('additional_data')->nullable();
            $table->timestamps();

            $table->foreign('incident_type_id')->references('id')->on('incident_types')->onDelete('set null');
            $table->foreign('incident_status_id')->references('id')->on('incident_statuses')->onDelete('set null');
            $table->index('incident_date');
            $table->index(['incident_date', 'school_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_calendars');
    }
};