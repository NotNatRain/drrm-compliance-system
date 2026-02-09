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
        Schema::table('firesafety_school_information', function (Blueprint $table) {
            $table->longText('alerts')->nullable()->after('status');
            $table->longText('events')->nullable()->after('alerts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('firesafety_school_information', function (Blueprint $table) {
            $table->dropColumn(['alerts', 'events']);
        });
    }
};
