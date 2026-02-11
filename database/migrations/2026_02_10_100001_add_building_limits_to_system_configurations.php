<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('system_configurations', function (Blueprint $table) {
            $table->unsignedInteger('min_floors')->nullable()->after('description');
            $table->unsignedInteger('total_rooms')->nullable()->after('min_floors');
        });
    }

    public function down(): void
    {
        Schema::table('system_configurations', function (Blueprint $table) {
            $table->dropColumn(['min_floors', 'total_rooms']);
        });
    }
};
