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
        Schema::table('firesafety_evacuationplans', function (Blueprint $table) {
            if (!Schema::hasColumn('firesafety_evacuationplans', 'safety_features_installed')) {
                $table->text('safety_features_installed')->nullable()->after('secondary_route');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('firesafety_evacuationplans', function (Blueprint $table) {
            if (Schema::hasColumn('firesafety_evacuationplans', 'safety_features_installed')) {
                $table->dropColumn('safety_features_installed');
            }
        });
    }
};
