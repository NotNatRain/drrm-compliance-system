<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $sharedSpaceId = DB::table('system_configurations')
            ->where('config_type', 'calculated_priority')
            ->whereRaw('LOWER(name) = ?', ['shared space'])
            ->value('id');

        if (!$sharedSpaceId) {
            $sortOrder = (int) DB::table('system_configurations')
                ->where('config_type', 'calculated_priority')
                ->max('sort_order');

            $sharedSpaceId = DB::table('system_configurations')->insertGetId([
                'config_type' => 'calculated_priority',
                'name' => 'Shared Space',
                'description' => 'Rooms under this priority can host up to 2 extinguishers.',
                'max_rooms_covered' => 1,
                'sort_order' => $sortOrder + 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Re-parent existing "Classroom and Administration" room type(s) to Shared Space.
        $roomTypeIds = DB::table('system_configurations')
            ->where('config_type', 'room_type')
            ->whereRaw("LOWER(name) LIKE '%classroom%'")
            ->whereRaw("LOWER(name) LIKE '%administration%'")
            ->pluck('id')
            ->map(fn ($v) => (int) $v)
            ->values()
            ->all();

        if (!empty($roomTypeIds)) {
            DB::table('system_configurations')
                ->whereIn('id', $roomTypeIds)
                ->update([
                    'parent_id' => $sharedSpaceId,
                    'updated_at' => now(),
                ]);
        }

        // Update existing room snapshots so they immediately follow the new priority.
        $roomQuery = DB::table('fire_safety_rooms')
            ->whereRaw("LOWER(room_type) LIKE '%classroom%'")
            ->whereRaw("LOWER(room_type) LIKE '%administration%'");

        if (!empty($roomTypeIds)) {
            $roomQuery->orWhereIn('room_type_config_id', $roomTypeIds);
        }

        $roomQuery->update([
            'calculated_priority_label' => 'Shared Space',
            'coverage_limit' => 1,
        ]);
    }

    public function down(): void
    {
        // Intentionally left as no-op to avoid losing user-customized configuration links.
    }
};
