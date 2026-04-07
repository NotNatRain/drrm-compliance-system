<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the centralized `schools` table.
     * This is the single source of truth for ALL school information
     * across Fire Safety, Incident, Typhoon/Flood, and Comprehensive Assessment modules.
     *
     * Columns are organized into groups:
     *   - Core Identification (from fire safety + comprehensive)
     *   - Administrative (from fire safety + comprehensive)
     *   - Location (from incident + comprehensive)
     *   - Fire Safety specific fields
     *   - Typhoon/Flood specific fields
     *   - Incident specific fields
     */
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();

            // ── Core Identification ──────────────────────────────
            // school_id comes from firesafety_school_information.school_id (the DepEd school ID string)
            // school_id_number comes from cmpr_schl_sfty_schools.school_id_number
            // In practice these are the same value; we keep both for backward compat during transition
            $table->string('school_id')->nullable()->comment('DepEd school ID from fire safety module');
            $table->string('school_id_number')->nullable()->comment('DepEd school ID from comprehensive module');
            $table->string('school_name')->comment('Canonical school name');
            $table->text('address')->nullable();

            // ── Administrative ───────────────────────────────────
            $table->string('school_head')->nullable();
            $table->string('drrm_coordinator')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('contact_number_2')->nullable();

            // ── Location ─────────────────────────────────────────
            $table->string('district')->nullable();
            $table->string('division')->nullable();
            $table->string('region')->nullable();

            // ── Fire Safety Specific ─────────────────────────────
            $table->json('evacuation_map_layout')->nullable();
            $table->string('attached_evacuation_map')->nullable();
            $table->string('fire_safety_status')->default('unconfigured');
            $table->longText('alerts')->nullable();
            $table->longText('events')->nullable();
            $table->longText('replies')->nullable();

            // ── Typhoon/Flood Specific ───────────────────────────
            $table->string('identification')->nullable();
            $table->string('evacuation_identification')->nullable()->comment('Evacuation center identification');
            $table->string('evacuation_location')->nullable()->comment('Readable evacuation center location');
            $table->unsignedInteger('evacuation_capacity')->default(0);
            $table->string('operational_status')->default('operational')->comment('operational|partial|closed');
            $table->string('evacuation_status')->default('cleared')->comment('occupied|full|cleared (was usage_status)');
            $table->string('occupancy_safety')->default('safe')->comment('safe|warning|critical');
            $table->text('emergency_resources')->nullable();
            $table->string('emergency_resources_status')->nullable()->comment('Low/Moderate/High');
            $table->string('needs_summary')->nullable()->comment('Quick badge summary on dashboard');
            $table->string('monitoring_status')->nullable();
            $table->string('reports_status')->nullable();

            // ── Incident Specific ────────────────────────────────
            $table->integer('incident_count')->default(0);
            $table->date('last_incident_date')->nullable();

            // ── Timestamps ───────────────────────────────────────
            $table->timestamps();

            // ── Indexes ──────────────────────────────────────────
            $table->index('school_name');
            $table->index('school_id');
            $table->index('school_id_number');
            $table->index('district');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
