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
        Schema::table('incident_calendars', function (Blueprint $table) {
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('accepted')->after('id');
            $table->text('rejection_reason')->nullable()->after('status');
            $table->unsignedBigInteger('contributor_id')->nullable()->after('rejection_reason');
            
            $table->foreign('contributor_id')->references('id')->on('users')->onDelete('set null');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incident_calendars', function (Blueprint $table) {
            $table->dropForeign(['contributor_id']);
            $table->dropColumn(['status', 'contributor_id']);
        });
    }
};
