<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('typ_fld_families', function (Blueprint $table) {
            if (!Schema::hasColumn('typ_fld_families', 'building_id')) {
                $table->unsignedBigInteger('building_id')->nullable()->after('school_id');
            }
            if (!Schema::hasColumn('typ_fld_families', 'room_id')) {
                $table->unsignedBigInteger('room_id')->nullable()->after('building_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('typ_fld_families', function (Blueprint $table) {
            if (Schema::hasColumn('typ_fld_families', 'room_id')) {
                $table->dropColumn('room_id');
            }
            if (Schema::hasColumn('typ_fld_families', 'building_id')) {
                $table->dropColumn('building_id');
            }
        });
    }
};
