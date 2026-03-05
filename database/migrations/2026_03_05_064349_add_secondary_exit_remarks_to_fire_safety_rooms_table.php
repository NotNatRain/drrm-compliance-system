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
            $table->text('secondary_exit_remarks')->nullable()->after('has_secondary_exit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fire_safety_rooms', function (Blueprint $table) {
            $table->dropColumn('secondary_exit_remarks');
        });
    }
};
