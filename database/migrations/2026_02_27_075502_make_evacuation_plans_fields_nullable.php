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
            $table->text('exits')->nullable()->change();
            $table->text('routes')->nullable()->change();
            $table->text('areas')->nullable()->change();
            // Ensure other common fields are nullable if they exist
            if (Schema::hasColumn('firesafety_evacuationplans', 'primary_assembly_area')) {
                $table->string('primary_assembly_area')->nullable()->change();
            }
            if (Schema::hasColumn('firesafety_evacuationplans', 'secondary_assembly_area')) {
                $table->string('secondary_assembly_area')->nullable()->change();
            }
            if (Schema::hasColumn('firesafety_evacuationplans', 'assembly_capacity')) {
                $table->integer('assembly_capacity')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('firesafety_evacuationplans', function (Blueprint $table) {
            $table->text('exits')->nullable(false)->change();
            $table->text('routes')->nullable(false)->change();
            $table->text('areas')->nullable(false)->change();
        });
    }
};
