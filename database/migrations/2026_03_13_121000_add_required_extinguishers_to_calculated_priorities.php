<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('system_configurations', function (Blueprint $table) {
            if (!Schema::hasColumn('system_configurations', 'required_extinguishers')) {
                $table->unsignedTinyInteger('required_extinguishers')->default(1)->after('max_rooms_covered');
            }
        });

        DB::table('system_configurations')
            ->where('config_type', 'calculated_priority')
            ->whereNull('required_extinguishers')
            ->update(['required_extinguishers' => 1]);

        DB::table('system_configurations')
            ->where('config_type', 'calculated_priority')
            ->update([
                'required_extinguishers' => DB::raw('COALESCE(required_extinguishers, 1)'),
            ]);

        DB::table('system_configurations')
            ->where('config_type', 'calculated_priority')
            ->whereRaw('LOWER(name) = ?', ['shared space'])
            ->update([
                'max_rooms_covered' => 1,
                'required_extinguishers' => 2,
                'updated_at' => now(),
            ]);

        DB::table('system_configurations')
            ->where('config_type', 'calculated_priority')
            ->whereRaw('LOWER(name) != ?', ['shared space'])
            ->update([
                'required_extinguishers' => 1,
                'updated_at' => now(),
            ]);

        $sharedSpaceTypeIds = DB::table('system_configurations as room_type')
            ->join('system_configurations as priority', 'priority.id', '=', 'room_type.parent_id')
            ->where('room_type.config_type', 'room_type')
            ->where('priority.config_type', 'calculated_priority')
            ->whereRaw('LOWER(priority.name) = ?', ['shared space'])
            ->pluck('room_type.id')
            ->map(fn ($value) => (int) $value)
            ->values()
            ->all();

        if (!empty($sharedSpaceTypeIds)) {
            DB::table('fire_safety_rooms')
                ->whereIn('room_type_config_id', $sharedSpaceTypeIds)
                ->update([
                    'calculated_priority_label' => 'Shared Space',
                    'coverage_limit' => 1,
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('system_configurations', function (Blueprint $table) {
            if (Schema::hasColumn('system_configurations', 'required_extinguishers')) {
                $table->dropColumn('required_extinguishers');
            }
        });
    }
};
