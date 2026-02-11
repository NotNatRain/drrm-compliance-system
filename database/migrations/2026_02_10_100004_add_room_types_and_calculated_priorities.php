<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('system_configurations', function (Blueprint $table) {
            if (!Schema::hasColumn('system_configurations', 'max_rooms_covered')) {
                $table->unsignedTinyInteger('max_rooms_covered')->nullable()->after('pressure_max');
            }
        });

        // Seed sensible defaults if missing (admin can edit later)
        $sharedId = DB::table('system_configurations')
            ->where('config_type', 'calculated_priority')
            ->where('name', 'Shared Coverage (Up to 3 Classrooms)')
            ->value('id');
        if (!$sharedId) {
            $sharedId = DB::table('system_configurations')->insertGetId([
                'config_type' => 'calculated_priority',
                'name' => 'Shared Coverage (Up to 3 Classrooms)',
                'description' => 'Shared coverage priority for classroom-like rooms',
                'max_rooms_covered' => 3,
                'sort_order' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $dedicatedId = DB::table('system_configurations')
            ->where('config_type', 'calculated_priority')
            ->where('name', 'Dedicated / Limited Shared')
            ->value('id');
        if (!$dedicatedId) {
            $dedicatedId = DB::table('system_configurations')->insertGetId([
                'config_type' => 'calculated_priority',
                'name' => 'Dedicated / Limited Shared',
                'description' => 'Dedicated / limited share priority for specialized rooms',
                'max_rooms_covered' => 2,
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create default Room Types (no "Others" / no "General Use")
        $defaults = [
            ['name' => 'Classroom', 'parent_id' => $sharedId, 'sort_order' => 0],
            ['name' => 'Department', 'parent_id' => $sharedId, 'sort_order' => 1],
            ['name' => 'Library', 'parent_id' => $sharedId, 'sort_order' => 2],
            ['name' => 'Laboratory', 'parent_id' => $dedicatedId, 'sort_order' => 3],
            ['name' => 'Clinic', 'parent_id' => $dedicatedId, 'sort_order' => 4],
            ['name' => 'Storage', 'parent_id' => $dedicatedId, 'sort_order' => 5],
            ['name' => 'Auxiliary', 'parent_id' => $dedicatedId, 'sort_order' => 6],
            ['name' => 'Office', 'parent_id' => $dedicatedId, 'sort_order' => 7],
        ];

        foreach ($defaults as $d) {
            $exists = DB::table('system_configurations')
                ->where('config_type', 'room_type')
                ->where('name', $d['name'])
                ->exists();
            if (!$exists) {
                DB::table('system_configurations')->insert([
                    'config_type' => 'room_type',
                    'parent_id' => $d['parent_id'],
                    'name' => $d['name'],
                    'description' => null,
                    'sort_order' => $d['sort_order'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        // Keep seeded rows (safe rollback). Only drop the column.
        Schema::table('system_configurations', function (Blueprint $table) {
            if (Schema::hasColumn('system_configurations', 'max_rooms_covered')) {
                $table->dropColumn('max_rooms_covered');
            }
        });
    }
};

