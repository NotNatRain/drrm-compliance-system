<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cmpr_schl_sfty_sumFindings')) {
            return;
        }

        Schema::table('cmpr_schl_sfty_sumFindings', function (Blueprint $table) {
            if (!Schema::hasColumn('cmpr_schl_sfty_sumFindings', 'floor_number')) {
                $table->unsignedInteger('floor_number')->nullable()->after('building_id');
            }
            if (!Schema::hasColumn('cmpr_schl_sfty_sumFindings', 'room_code')) {
                $table->string('room_code', 100)->nullable()->after('floor_number');
            }
            if (!Schema::hasColumn('cmpr_schl_sfty_sumFindings', 'chairs_count')) {
                $table->unsignedInteger('chairs_count')->default(0)->after('remarks');
            }
            if (!Schema::hasColumn('cmpr_schl_sfty_sumFindings', 'tables_count')) {
                $table->unsignedInteger('tables_count')->default(0)->after('chairs_count');
            }
            if (!Schema::hasColumn('cmpr_schl_sfty_sumFindings', 'tv_count')) {
                $table->unsignedInteger('tv_count')->default(0)->after('tables_count');
            }
            if (!Schema::hasColumn('cmpr_schl_sfty_sumFindings', 'electric_fan_count')) {
                $table->unsignedInteger('electric_fan_count')->default(0)->after('tv_count');
            }
            if (!Schema::hasColumn('cmpr_schl_sfty_sumFindings', 'ceiling_fan_count')) {
                $table->unsignedInteger('ceiling_fan_count')->default(0)->after('electric_fan_count');
            }
            if (!Schema::hasColumn('cmpr_schl_sfty_sumFindings', 'water_dispenser_count')) {
                $table->unsignedInteger('water_dispenser_count')->default(0)->after('ceiling_fan_count');
            }
            if (!Schema::hasColumn('cmpr_schl_sfty_sumFindings', 'window_type')) {
                $table->string('window_type')->nullable()->after('water_dispenser_count');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('cmpr_schl_sfty_sumFindings')) {
            return;
        }

        Schema::table('cmpr_schl_sfty_sumFindings', function (Blueprint $table) {
            $columns = [
                'floor_number',
                'room_code',
                'chairs_count',
                'tables_count',
                'tv_count',
                'electric_fan_count',
                'ceiling_fan_count',
                'water_dispenser_count',
                'window_type',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('cmpr_schl_sfty_sumFindings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
