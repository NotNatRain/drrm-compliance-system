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
        Schema::table('firesafety_buildings', function (Blueprint $table) {
            if (!Schema::hasColumn('firesafety_buildings', 'max_floors')) {
                $table->integer('max_floors')->default(1)->after('floors');
            }
            if (!Schema::hasColumn('firesafety_buildings', 'max_rooms')) {
                $table->integer('max_rooms')->default(1)->after('rooms');
            }
        });

        // Initialize max_floors and max_rooms with current floors and rooms
        DB::table('firesafety_buildings')->update([
            'max_floors' => DB::raw('floors'),
            'max_rooms' => DB::raw('rooms')
        ]);

        Schema::table('firesafety_fire_extinguishers', function (Blueprint $table) {
            // Change status to string to accommodate new choices and make it more flexible
            $table->string('status')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('firesafety_buildings', function (Blueprint $table) {
            $table->dropColumn(['max_floors', 'max_rooms']);
        });

        Schema::table('firesafety_fire_extinguishers', function (Blueprint $table) {
            // Revert to original enum if possible, or just keep as string
            $table->enum('status', ['active', 'expired', 'maintenance', 'missing'])->change();
        });
    }
};
