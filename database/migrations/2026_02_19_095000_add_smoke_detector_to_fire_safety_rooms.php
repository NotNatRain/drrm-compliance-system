<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('fire_safety_rooms', function (Blueprint $table) {
            if (!Schema::hasColumn('fire_safety_rooms', 'has_smoke_detector')) {
                $table->boolean('has_smoke_detector')->default(false)->after('room_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fire_safety_rooms', function (Blueprint $table) {
            if (Schema::hasColumn('fire_safety_rooms', 'has_smoke_detector')) {
                $table->dropColumn('has_smoke_detector');
            }
        });
    }
};
