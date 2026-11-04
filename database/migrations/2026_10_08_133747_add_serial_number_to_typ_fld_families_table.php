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
        Schema::table('typ_fld_families', function (Blueprint $table) {
            if (!Schema::hasColumn('typ_fld_families', 'serial_number')) {
                $table->string('serial_number', 100)->nullable()->after('room_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('typ_fld_families', function (Blueprint $table) {
            if (Schema::hasColumn('typ_fld_families', 'serial_number')) {
                $table->dropColumn('serial_number');
            }
        });
    }
};
