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
            $table->boolean('has_secondary_exit')->default(false)->after('has_smoke_detector');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fire_safety_rooms', function (Blueprint $table) {
            $table->dropColumn('has_secondary_exit');
        });
    }
};
