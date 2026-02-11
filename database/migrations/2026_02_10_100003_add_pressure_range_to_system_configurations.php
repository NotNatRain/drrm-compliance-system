<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('system_configurations', function (Blueprint $table) {
            $table->decimal('pressure_min', 8, 2)->nullable()->after('total_rooms');
            $table->decimal('pressure_max', 8, 2)->nullable()->after('pressure_min');
        });
    }

    public function down(): void
    {
        Schema::table('system_configurations', function (Blueprint $table) {
            $table->dropColumn(['pressure_min', 'pressure_max']);
        });
    }
};
