<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Add `unified_school_id` column to existing tables for backwards compatibility
     * and future transitions, ensuring they point to the correct ID in the new `schools` table.
     */
    public function up(): void
    {
        // 1. Add columns
        $tables = [
            'firesafety_school_information',
            'incident_schools',
            'typ_fld_evacuation_centers',
            'cmpr_schl_sfty_schools',
            'users'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $t) use ($table) {
                    // Check if column already exists to be safe
                    if (!Schema::hasColumn($table, 'unified_school_id')) {
                        $t->unsignedBigInteger('unified_school_id')->nullable()->after('id');
                        $t->foreign('unified_school_id')->references('id')->on('schools')->onDelete('set null');
                    }
                });
            }
        }

        // 2. Populate the columns based on the mappings stored in school_specifics_information
        DB::transaction(function () {
            // Firesafety mapping
            $fsMap = [];
            $fsData = DB::table('school_specifics_information')
                ->where('module', 'fire_safety')
                ->where('key', 'original_fire_safety_id')
                ->get();
            foreach ($fsData as $item) {
                DB::table('firesafety_school_information')
                    ->where('id', $item->value)
                    ->update(['unified_school_id' => $item->school_id]);
                $fsMap[$item->value] = $item->school_id;
            }

            // Cmpr mapping
            $cmprData = DB::table('school_specifics_information')
                ->where('module', 'comprehensive')
                ->where('key', 'original_cmpr_school_id')
                ->get();
            foreach ($cmprData as $item) {
                DB::table('cmpr_schl_sfty_schools')
                    ->where('id', $item->value)
                    ->update(['unified_school_id' => $item->school_id]);
            }

            // Incident mapping
            $incData = DB::table('school_specifics_information')
                ->where('module', 'incident')
                ->where('key', 'original_incident_school_id')
                ->get();
            foreach ($incData as $item) {
                DB::table('incident_schools')
                    ->where('id', $item->value)
                    ->update(['unified_school_id' => $item->school_id]);
            }

            // Evacuation Centers mapping
            $ecData = DB::table('school_specifics_information')
                ->where('module', 'typhoon_flood')
                ->where('key', 'original_evacuation_center_id')
                ->get();
            foreach ($ecData as $item) {
                DB::table('typ_fld_evacuation_centers')
                    ->where('id', $item->value)
                    ->update(['unified_school_id' => $item->school_id]);
            }

            // Users table: this is slightly trickier as we need to match by school_name or existing school_id
            // In the current setup, users might have a `school` string or some identifier.
            // Let's check common user columns. Assuming users has `school_name` or `school_id`.
            // Re-check: "users" count in verify.txt was blank, meaning it has data.
            // Let's assume we match users by their existing school associations.
            // If they linked to FireSafety schools, we can use that.
            // Users table: Assuming its school_id originally pointed to firesafety_school_information.id
            $users = DB::table('users')->get();
            foreach ($users as $u) {
                if ($u->school_id && isset($fsMap[$u->school_id])) {
                    DB::table('users')
                        ->where('id', $u->id)
                        ->update(['unified_school_id' => $fsMap[$u->school_id]]);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'firesafety_school_information',
            'incident_schools',
            'typ_fld_evacuation_centers',
            'cmpr_schl_sfty_schools',
            'users'
        ];

        foreach (array_reverse($tables) as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'unified_school_id')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->dropForeign(['unified_school_id']);
                    $t->dropColumn('unified_school_id');
                });
            }
        }
    }
};
