<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * When a school is deleted, archive rows for that school should persist (SET NULL).
     */
    public function up(): void
    {
        Schema::table('fire_safety_archives', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
        });

        DB::statement('ALTER TABLE fire_safety_archives MODIFY school_id BIGINT UNSIGNED NULL');

        Schema::table('fire_safety_archives', function (Blueprint $table) {
            $table->foreign('school_id')->references('id')->on('firesafety_school_information')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fire_safety_archives', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
        });

        DB::statement('ALTER TABLE fire_safety_archives MODIFY school_id BIGINT UNSIGNED NOT NULL');

        Schema::table('fire_safety_archives', function (Blueprint $table) {
            $table->foreign('school_id')->references('id')->on('firesafety_school_information')->onDelete('cascade');
        });
    }
};
