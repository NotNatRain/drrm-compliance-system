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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('typhoon_school_id')->nullable()->after('school_id');
            $table->boolean('needs_fs_registration')->default(false)->after('typhoon_school_id');
            $table->boolean('needs_tf_registration')->default(false)->after('needs_fs_registration');

            $table->foreign('typhoon_school_id')->references('id')->on('typ_fld_evacuation_centers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['typhoon_school_id']);
            $table->dropColumn(['typhoon_school_id', 'needs_fs_registration', 'needs_tf_registration']);
        });
    }
};
