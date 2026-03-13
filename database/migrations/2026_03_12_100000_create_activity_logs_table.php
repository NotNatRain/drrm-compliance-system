<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('role', 32)->nullable();
            $table->string('activity');           // What did they do (e.g. "Created building", "Updated extinguisher")
            $table->unsignedBigInteger('school_id')->nullable();
            $table->string('school_name')->nullable();  // Display name (for incident school_name or resolved from school_id)
            $table->string('module', 64);         // fire_safety, typhoon_flood, incident_checklist, comprehensive_safety, hazard_mapping
            $table->text('notes')->nullable();    // Remarks/notes/comments from create/update/delete
            $table->timestamps();

            $table->index(['module', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index(['school_id', 'module']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
