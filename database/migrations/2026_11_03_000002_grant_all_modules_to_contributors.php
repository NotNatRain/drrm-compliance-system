<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $allModules = json_encode([
            'fire_safety',
            'typhoon_flood',
            'incident_checklist',
            'comprehensive_school_safety',
            'drill_monitoring',
            'hazard_mapping',
            'casualty_tracking',
            'damage_reports',
            'inventory_storage',
            'heat_index',
        ]);

        DB::table('users')
            ->where('role', 'contributor')
            ->update([
                'module_access' => $allModules,
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
