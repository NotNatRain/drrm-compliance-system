<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the `school_specifics_information` table.
     * This table stores module-specific metadata or extra info for each school.
     */
    public function up(): void
    {
        Schema::create('school_specifics_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('module')->comment('fire_safety|incident|typhoon_flood|comprehensive');
            $table->string('key')->comment('The metadata key (e.g., original_id, needs_summary)');
            $table->longText('value')->nullable();
            $table->timestamps();

            // Index for faster lookups
            $table->index(['school_id', 'module']);
            // Optional: unique constraint to prevent duplicate keys for the same school/module
            $table->unique(['school_id', 'module', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_specifics_information');
    }
};
