<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fire_safety_rooms', function (Blueprint $table) {
            if (!Schema::hasColumn('fire_safety_rooms', 'engineer_last_inspection_date')) {
                $table->date('engineer_last_inspection_date')->nullable()->after('nearest_extinguisher_room_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('fire_safety_rooms', function (Blueprint $table) {
            if (Schema::hasColumn('fire_safety_rooms', 'engineer_last_inspection_date')) {
                $table->dropColumn('engineer_last_inspection_date');
            }
        });
    }
};
