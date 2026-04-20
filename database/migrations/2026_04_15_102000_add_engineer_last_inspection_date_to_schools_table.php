<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add the school-level engineer inspection date.
     */
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            if (!Schema::hasColumn('schools', 'engineer_last_inspection_date')) {
                $table->date('engineer_last_inspection_date')->nullable()->after('number_gates');
            }
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            if (Schema::hasColumn('schools', 'engineer_last_inspection_date')) {
                $table->dropColumn('engineer_last_inspection_date');
            }
        });
    }
};