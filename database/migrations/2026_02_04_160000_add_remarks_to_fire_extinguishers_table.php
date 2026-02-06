<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('firesafety_fire_extinguishers', function (Blueprint $table) {
            if (!Schema::hasColumn('firesafety_fire_extinguishers', 'remarks')) {
                $table->text('remarks')->nullable()->after('date_checked');
            }
        });
    }

    public function down(): void
    {
        Schema::table('firesafety_fire_extinguishers', function (Blueprint $table) {
            if (Schema::hasColumn('firesafety_fire_extinguishers', 'remarks')) {
                $table->dropColumn('remarks');
            }
        });
    }
};
