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
            // Add building_id if it doesn't exist
            if (!Schema::hasColumn('firesafety_evacuationplans', 'building_id')) {
                $table->unsignedBigInteger('building_id')->after('school_id')->nullable();
            }
            
            // Add primary_route if it doesn't exist
            if (!Schema::hasColumn('firesafety_evacuationplans', 'primary_route')) {
                $table->text('primary_route')->nullable()->after('routes');
            }
            
            // Add secondary_route if it doesn't exist
            if (!Schema::hasColumn('firesafety_evacuationplans', 'secondary_route')) {
                $table->text('secondary_route')->nullable()->after('primary_route');
            }
            
            // Add primary_assembly_area if it doesn't exist
            if (!Schema::hasColumn('firesafety_evacuationplans', 'primary_assembly_area')) {
                $table->string('primary_assembly_area')->nullable()->after('areas');
            }
            
            // Add secondary_assembly_area if it doesn't exist
            if (!Schema::hasColumn('firesafety_evacuationplans', 'secondary_assembly_area')) {
                $table->string('secondary_assembly_area')->nullable()->after('primary_assembly_area');
            }
            
            // Add assembly_capacity if it doesn't exist
            if (!Schema::hasColumn('firesafety_evacuationplans', 'assembly_capacity')) {
                $table->integer('assembly_capacity')->nullable()->after('secondary_assembly_area');
            }
            
            // Add emergency_contacts if it doesn't exist
            if (!Schema::hasColumn('firesafety_evacuationplans', 'emergency_contacts')) {
                $table->text('emergency_contacts')->nullable();
            }
            
            // Add special_instructions if it doesn't exist
            if (!Schema::hasColumn('firesafety_evacuationplans', 'special_instructions')) {
                $table->text('special_instructions')->nullable();
            }
            
            // Add foreign key constraint (only if building_id exists)
            if (Schema::hasColumn('firesafety_evacuationplans', 'building_id') && 
                Schema::hasTable('firesafety_buildings')) {
                $table->foreign('building_id')
                      ->references('id')
                      ->on('firesafety_buildings')
                      ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('firesafety_evacuationplans', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['building_id']);
            
            // Drop columns
            $table->dropColumn([
                'building_id',
                'primary_route',
                'secondary_route',
                'primary_assembly_area',
                'secondary_assembly_area',
                'assembly_capacity',
                'emergency_contacts',
                'special_instructions'
            ]);
        });
    }
};