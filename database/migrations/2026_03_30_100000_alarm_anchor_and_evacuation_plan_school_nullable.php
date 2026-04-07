<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('firesafety_alarm_systems')) {
            if (! Schema::hasColumn('firesafety_alarm_systems', 'anchor_building_id')) {
                Schema::table('firesafety_alarm_systems', function (Blueprint $table) {
                    $table->foreignId('anchor_building_id')
                        ->nullable()
                        ->constrained('firesafety_buildings')
                        ->nullOnDelete();
                });
            }
        }

        if (Schema::hasTable('firesafety_evacuationplans') && Schema::hasColumn('firesafety_evacuationplans', 'school_id')) {
            try {
                DB::statement('ALTER TABLE firesafety_evacuationplans MODIFY school_id BIGINT UNSIGNED NULL');
            } catch (\Throwable $e) {
                // ignore if driver/permissions differ
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('firesafety_alarm_systems') && Schema::hasColumn('firesafety_alarm_systems', 'anchor_building_id')) {
            Schema::table('firesafety_alarm_systems', function (Blueprint $table) {
                $table->dropForeign(['anchor_building_id']);
                $table->dropColumn('anchor_building_id');
            });
        }
    }
};
