<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add source field to storage inventory.
     */
    public function up(): void
    {
        if (Schema::hasTable('cmpr_schl_sfty_storage') && !Schema::hasColumn('cmpr_schl_sfty_storage', 'source_from')) {
            Schema::table('cmpr_schl_sfty_storage', function (Blueprint $table) {
                $table->string('source_from')->nullable()->after('item_type');
            });
        }
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        if (Schema::hasTable('cmpr_schl_sfty_storage') && Schema::hasColumn('cmpr_schl_sfty_storage', 'source_from')) {
            Schema::table('cmpr_schl_sfty_storage', function (Blueprint $table) {
                $table->dropColumn('source_from');
            });
        }
    }
};
