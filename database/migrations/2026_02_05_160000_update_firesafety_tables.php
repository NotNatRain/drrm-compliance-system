<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('firesafety_buildings', function (Blueprint $table) {
            if (!Schema::hasColumn('firesafety_buildings', 'required_extinguishers')) {
                $table->integer('required_extinguishers')->default(0)->after('rooms');
            }
        });

        Schema::table('fire_safety_rooms', function (Blueprint $table) {
            if (!Schema::hasColumn('fire_safety_rooms', 'nearest_extinguisher_room_id')) {
                $table->foreignId('nearest_extinguisher_room_id')->nullable()->constrained('fire_safety_rooms')->nullOnDelete()->after('floor_no');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('firesafety_buildings', function (Blueprint $table) {
            if (Schema::hasColumn('firesafety_buildings', 'required_extinguishers')) {
                $table->dropColumn('required_extinguishers');
            }
        });

        Schema::table('fire_safety_rooms', function (Blueprint $table) {
            if (Schema::hasColumn('fire_safety_rooms', 'nearest_extinguisher_room_id')) {
                $table->dropForeign(['nearest_extinguisher_room_id']);
                $table->dropColumn('nearest_extinguisher_room_id');
            }
        });
    }
};
