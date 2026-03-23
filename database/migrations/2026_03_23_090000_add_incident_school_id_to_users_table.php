<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('incident_school_id')->nullable()->after('typhoon_school_id');
            $table->foreign('incident_school_id')
                ->references('id')
                ->on('incident_schools')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['incident_school_id']);
            $table->dropColumn('incident_school_id');
        });
    }
};
