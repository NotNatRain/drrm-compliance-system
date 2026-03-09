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
            if (!Schema::hasColumn('fire_safety_rooms', 'smoke_detector_required')) {
                $table->boolean('smoke_detector_required')->default(false)->after('has_smoke_detector');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fire_safety_rooms', function (Blueprint $table) {
            if (Schema::hasColumn('fire_safety_rooms', 'smoke_detector_required')) {
                $table->dropColumn('smoke_detector_required');
            }
        });
    }
};
