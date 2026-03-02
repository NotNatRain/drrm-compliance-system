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
            if (!Schema::hasColumn('firesafety_evacuationplans', 'primary_route')) {
                $table->text('primary_route')->nullable()->after('routes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('firesafety_evacuationplans', function (Blueprint $table) {
            if (Schema::hasColumn('firesafety_evacuationplans', 'primary_route')) {
                $table->dropColumn('primary_route');
            }
        });
    }
};
