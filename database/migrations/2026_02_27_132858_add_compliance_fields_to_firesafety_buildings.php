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
        Schema::table('firesafety_buildings', function (Blueprint $table) {
            if (!Schema::hasColumn('firesafety_buildings', 'safety_score')) {
                $table->integer('safety_score')->default(0)->after('required_extinguishers');
            }
            if (!Schema::hasColumn('firesafety_buildings', 'compliance_status')) {
                $table->string('compliance_status')->nullable()->after('safety_score');
            }
            if (!Schema::hasColumn('firesafety_buildings', 'compliance_reason')) {
                $table->text('compliance_reason')->nullable()->after('compliance_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('firesafety_buildings', function (Blueprint $table) {
            if (Schema::hasColumn('firesafety_buildings', 'compliance_reason')) {
                $table->dropColumn('compliance_reason');
            }
            if (Schema::hasColumn('firesafety_buildings', 'compliance_status')) {
                $table->dropColumn('compliance_status');
            }
            if (Schema::hasColumn('firesafety_buildings', 'safety_score')) {
                $table->dropColumn('safety_score');
            }
        });
    }
};
