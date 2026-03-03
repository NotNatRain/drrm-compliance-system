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
        Schema::table('firesafety_evacuationplans', function (Blueprint $table) {
            // Add unique key to prevent multiple plans per building/school and duplicate plan numbers
            // We rely on migrations running only once; adding if-not-exists style is not supported easily so we just add them.
            $table->unique(['school_id', 'building_id'], 'evacuationplans_school_building_unique');
            $table->unique(['school_id', 'plan_no'], 'evacuationplans_school_planno_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('firesafety_evacuationplans', function (Blueprint $table) {
            $table->dropUnique('evacuationplans_school_building_unique');
            $table->dropUnique('evacuationplans_school_planno_unique');
        });
    }
};
