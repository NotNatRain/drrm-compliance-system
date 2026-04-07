<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            if (!Schema::hasColumn('schools', 'number_students')) {
                $table->unsignedInteger('number_students')->default(0)->after('evacuation_capacity');
            }
            if (!Schema::hasColumn('schools', 'number_personnel')) {
                $table->unsignedInteger('number_personnel')->default(0)->after('number_students');
            }
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            if (Schema::hasColumn('schools', 'number_personnel')) {
                $table->dropColumn('number_personnel');
            }
            if (Schema::hasColumn('schools', 'number_students')) {
                $table->dropColumn('number_students');
            }
        });
    }
};

