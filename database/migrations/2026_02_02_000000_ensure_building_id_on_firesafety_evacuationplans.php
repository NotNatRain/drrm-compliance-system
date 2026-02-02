<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ensures building_id and other expected columns exist on firesafety_evacuationplans.
     */
    public function up(): void
    {
        if (!Schema::hasTable('firesafety_evacuationplans')) {
            return;
        }

        Schema::table('firesafety_evacuationplans', function (Blueprint $table) {
            if (!Schema::hasColumn('firesafety_evacuationplans', 'building_id')) {
                $table->unsignedBigInteger('building_id')->nullable()->after('school_id');
            }
        });

        // Add foreign key in a separate call to avoid closure issues
        if (Schema::hasColumn('firesafety_evacuationplans', 'building_id')
            && Schema::hasTable('firesafety_buildings')) {
            Schema::table('firesafety_evacuationplans', function (Blueprint $table) {
                $table->foreign('building_id')
                    ->references('id')
                    ->on('firesafety_buildings')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('firesafety_evacuationplans') || !Schema::hasColumn('firesafety_evacuationplans', 'building_id')) {
            return;
        }

        Schema::table('firesafety_evacuationplans', function (Blueprint $table) {
            try {
                $table->dropForeign(['building_id']);
            } catch (\Throwable $e) {
                // Foreign key may not have been added
            }
            $table->dropColumn('building_id');
        });
    }
};
