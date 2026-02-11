<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Allow dynamic room types by converting enum to varchar (MySQL)
        // Existing values remain unchanged.
        try {
            DB::statement("ALTER TABLE fire_safety_rooms MODIFY room_type VARCHAR(50) NOT NULL");
        } catch (\Throwable $e) {
            // Ignore if already converted / DB driver differs.
        }

        Schema::table('fire_safety_rooms', function (Blueprint $table) {
            if (!Schema::hasColumn('fire_safety_rooms', 'room_type_config_id')) {
                $table->unsignedBigInteger('room_type_config_id')->nullable()->after('room_type');
                $table->foreign('room_type_config_id')
                    ->references('id')->on('system_configurations')
                    ->nullOnDelete();
            }
            if (!Schema::hasColumn('fire_safety_rooms', 'calculated_priority_label')) {
                $table->string('calculated_priority_label', 120)->nullable()->after('room_type_config_id');
            }
            if (!Schema::hasColumn('fire_safety_rooms', 'coverage_limit')) {
                $table->unsignedTinyInteger('coverage_limit')->nullable()->after('calculated_priority_label');
            }
        });
    }

    public function down(): void
    {
        Schema::table('fire_safety_rooms', function (Blueprint $table) {
            if (Schema::hasColumn('fire_safety_rooms', 'room_type_config_id')) {
                $table->dropForeign(['room_type_config_id']);
                $table->dropColumn('room_type_config_id');
            }
            if (Schema::hasColumn('fire_safety_rooms', 'calculated_priority_label')) {
                $table->dropColumn('calculated_priority_label');
            }
            if (Schema::hasColumn('fire_safety_rooms', 'coverage_limit')) {
                $table->dropColumn('coverage_limit');
            }
        });

        // Best-effort revert. (Enum restoration is not attempted.)
    }
};

