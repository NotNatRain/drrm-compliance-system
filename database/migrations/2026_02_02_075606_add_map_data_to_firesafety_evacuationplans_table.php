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
            if (!Schema::hasColumn('firesafety_evacuationplans', 'secondary_route')) {
                $table->text('secondary_route')->nullable()->after('routes');
            }
            if (!Schema::hasColumn('firesafety_evacuationplans', 'primary_assembly_area')) {
                $table->string('primary_assembly_area')->nullable()->after('areas');
            }
            if (!Schema::hasColumn('firesafety_evacuationplans', 'secondary_assembly_area')) {
                $table->string('secondary_assembly_area')->nullable()->after('primary_assembly_area');
            }
            if (!Schema::hasColumn('firesafety_evacuationplans', 'assembly_capacity')) {
                $table->integer('assembly_capacity')->nullable()->after('secondary_assembly_area');
            }
            if (!Schema::hasColumn('firesafety_evacuationplans', 'emergency_contacts')) {
                $table->text('emergency_contacts')->nullable()->after('assembly_capacity');
            }
            if (!Schema::hasColumn('firesafety_evacuationplans', 'special_instructions')) {
                $table->text('special_instructions')->nullable()->after('emergency_contacts');
            }
            if (!Schema::hasColumn('firesafety_evacuationplans', 'map_data')) {
                $table->longText('map_data')->nullable()->after('special_instructions');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('firesafety_evacuationplans', function (Blueprint $table) {
            $table->dropColumn([
                'secondary_route',
                'primary_assembly_area',
                'secondary_assembly_area',
                'assembly_capacity',
                'emergency_contacts',
                'special_instructions',
                'map_data'
            ]);
        });
    }
};
