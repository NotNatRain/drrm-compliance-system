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
        Schema::table('typ_fld_evacuation_centers', function (Blueprint $table) {
            if (!Schema::hasColumn('typ_fld_evacuation_centers', 'usage_status')) {
                $table->string('usage_status')->default('cleared')->after('occupancy_safety'); // occupied|full|cleared
            }
            if (!Schema::hasColumn('typ_fld_evacuation_centers', 'emergency_resources')) {
                $table->text('emergency_resources')->nullable()->after('usage_status');
            }
            if (!Schema::hasColumn('typ_fld_evacuation_centers', 'reports_status')) {
                $table->string('reports_status')->nullable()->after('monitoring_status');
            }
            if (!Schema::hasColumn('typ_fld_evacuation_centers', 'capacity')) {
                $table->unsignedInteger('capacity')->default(0)->after('location');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('typ_fld_evacuation_centers', function (Blueprint $table) {
            if (Schema::hasColumn('typ_fld_evacuation_centers', 'usage_status')) {
                $table->dropColumn('usage_status');
            }
            if (Schema::hasColumn('typ_fld_evacuation_centers', 'emergency_resources')) {
                $table->dropColumn('emergency_resources');
            }
            if (Schema::hasColumn('typ_fld_evacuation_centers', 'reports_status')) {
                $table->dropColumn('reports_status');
            }
            if (Schema::hasColumn('typ_fld_evacuation_centers', 'capacity')) {
                $table->dropColumn('capacity');
            }
        });
    }
};
