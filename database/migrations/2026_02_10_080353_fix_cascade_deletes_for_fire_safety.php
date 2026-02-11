<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tables mapping to fix
        $tables = [
            'firesafety_buildings' => 'school_id',
            'firesafety_fire_extinguishers' => 'school_id',
            'firesafety_alarm_systems' => 'school_id',
            'fire_safety_inspections' => 'school_id',
            'fire_safety_rooms' => 'school_id',
            'firesafety_evacuationplans' => 'school_id',
            'fire_safety_evacuation_drills' => 'school_id',
        ];

        foreach ($tables as $table => $column) {
            Schema::table($table, function (Blueprint $tableGroup) use ($column) {
                // Drop existing if it exists (might not be named standardly, so try/catch is safer or just use raw if needed)
                try {
                    $tableGroup->dropForeign([$column]);
                } catch (\Exception $e) {}
                
                $tableGroup->foreign($column)->references('id')->on('firesafety_school_information')->onDelete('cascade');
            });
        }
        
        // Also fix building_id relations for sub-items
        $buildingLinked = [
            'firesafety_fire_extinguishers' => 'building_id',
            'firesafety_alarm_systems' => 'building_id',
            'fire_safety_inspections' => 'building_id',
            'fire_safety_rooms' => 'building_id',
            'firesafety_evacuationplans' => 'building_id',
        ];

        foreach ($buildingLinked as $table => $column) {
            Schema::table($table, function (Blueprint $tableGroup) use ($column) {
                try {
                    $tableGroup->dropForeign([$column]);
                } catch (\Exception $e) {}
                
                $tableGroup->foreign($column)->references('id')->on('firesafety_buildings')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        // No simple reverse since we don't know the exact previous state for all
    }
};
