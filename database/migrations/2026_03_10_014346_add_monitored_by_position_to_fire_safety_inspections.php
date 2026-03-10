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
        Schema::table('fire_safety_inspections', function (Blueprint $table) {
            $table->string('monitored_by_position')->nullable()->after('monitored_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fire_safety_inspections', function (Blueprint $table) {
            $table->dropColumn('monitored_by_position');
        });
    }
};
