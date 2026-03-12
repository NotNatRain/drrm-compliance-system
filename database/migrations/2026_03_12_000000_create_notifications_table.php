<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('firesafety_notifications');
        Schema::dropIfExists('notifications');

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('compliance_type');  // fire_safety, typhoon_flood, incident_checklist, school_safety, hazard_mapping
            $table->string('module');            // inspection, alarm_due, room_approval, extinguisher_inspection, general
            $table->unsignedBigInteger('school_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('type');              // inspection, alarm_due, room_approval, extinguisher_inspection, general
            $table->string('title');
            $table->text('message');
            $table->string('action_type')->nullable();  // see_inspection, update_now, go_test, mark_read
            $table->string('action_url')->nullable();
            $table->json('action_data')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->index(['compliance_type', 'school_id', 'is_read']);
            $table->index(['compliance_type', 'user_id', 'is_read']);
            $table->index(['compliance_type', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
