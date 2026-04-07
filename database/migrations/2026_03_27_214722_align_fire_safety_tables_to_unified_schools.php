<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Migrate all Fire Safety related records to point to the new unified `schools` table.
     */
    public function up(): void
    {
        // The list of tables in Fire Safety that link to the old school information table
        $tables = [
            'firesafety_buildings',
            'firesafety_alarm_systems',
            'firesafety_evacuationplans',
            'fire_safety_evacuation_drills',
            'fire_safety_inspections',
            'fire_safety_archives',
            'notifications',
            'firesafety_fire_extinguishers',
            'fire_safety_rooms',
            'fire_safety_extinguisher_inspections',
            'fire_safety_school_snapshots'
        ];

        // Get the mapping from the specifics table
        // 'value' column stores the old ID from firesafety_school_information
        // 'school_id' column stores the new ID in the schools table
        $mapping = DB::table('school_specifics_information')
            ->where('module', 'fire_safety')
            ->where('key', 'original_fire_safety_id')
            ->pluck('school_id', 'value');

        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            // 1. Add the column if it doesn't exist
            if (Schema::hasColumn($table, 'school_id') && !Schema::hasColumn($table, 'unified_school_id')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->unsignedBigInteger('unified_school_id')->nullable()->after('school_id');
                });
            }

            if (!Schema::hasColumn($table, 'school_id')) {
                continue;
            }

            // 2. Map existing records
            foreach ($mapping as $oldId => $newId) {
                DB::table($table)
                    ->where('school_id', $oldId)
                    ->update(['unified_school_id' => $newId]);
            }
            
            // 3. Add Foreign Key after population
            try {
                Schema::table($table, function (Blueprint $t) use ($table) {
                    $t->foreign('unified_school_id')->references('id')->on('schools')->onDelete('cascade');
                });
            } catch (\Exception $e) {
                // Foreign key might already exist or table has incompatible data
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'firesafety_buildings',
            'firesafety_alarm_systems',
            'firesafety_evacuationplans',
            'fire_safety_evacuation_drills',
            'fire_safety_inspections',
            'fire_safety_archives',
            'notifications',
            'firesafety_fire_extinguishers',
            'fire_safety_rooms',
            'fire_safety_extinguisher_inspections',
            'fire_safety_school_snapshots'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'unified_school_id')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->dropForeign(['unified_school_id']);
                    $t->dropColumn('unified_school_id');
                });
            }
        }
    }
};
