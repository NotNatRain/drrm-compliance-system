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
        Schema::table('incident_checklists', function (Blueprint $table) {
            $table->boolean('is_default')->default(false)->after('label');
            $table->boolean('is_deleted')->default(false)->after('is_default');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incident_checklists', function (Blueprint $table) {
            $table->dropColumn('is_default');
            $table->dropColumn('is_deleted');
        });
    }
};
