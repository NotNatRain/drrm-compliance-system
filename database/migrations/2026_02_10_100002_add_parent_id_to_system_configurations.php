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
            $table->unsignedBigInteger('parent_id')->nullable()->after('config_type');
        });

        // Backfill: for each alarm_type, create default statuses (copy from existing global alarm_status)
        $alarmTypes = DB::table('system_configurations')->where('config_type', 'alarm_type')->orderBy('id')->get();
        $globalStatuses = DB::table('system_configurations')->where('config_type', 'alarm_status')->get();
        foreach ($alarmTypes as $type) {
            foreach ($globalStatuses as $s) {
                DB::table('system_configurations')->insert([
                    'config_type' => 'alarm_status',
                    'parent_id' => $type->id,
                    'name' => $s->name,
                    'description' => $s->description,
                    'code' => $s->code,
                    'category' => $s->category,
                    'color_class' => $s->color_class,
                    'sort_order' => $s->sort_order ?? 0,
                    'is_active' => $s->is_active ?? true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        // Remove old alarm_status rows (they had no parent_id before; now column exists so they are null)
        DB::table('system_configurations')->where('config_type', 'alarm_status')->whereNull('parent_id')->delete();
    }

    public function down(): void
    {
        Schema::table('system_configurations', function (Blueprint $table) {
            $table->dropColumn('parent_id');
        });
    }
};
