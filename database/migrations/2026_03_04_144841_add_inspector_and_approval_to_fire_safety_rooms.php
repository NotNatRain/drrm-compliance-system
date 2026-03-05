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
        Schema::table('fire_safety_rooms', function (Blueprint $blueprint) {
            $blueprint->unsignedBigInteger('last_inspector_id')->nullable()->after('nearest_extinguisher_room_id');
            $blueprint->string('approval_status')->nullable()->after('last_inspector_id'); // pending, approved, rejected
            $blueprint->text('approval_message')->nullable()->after('approval_status');

            $blueprint->foreign('last_inspector_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fire_safety_rooms', function (Blueprint $blueprint) {
            $blueprint->dropForeign(['last_inspector_id']);
            $blueprint->dropColumn(['last_inspector_id', 'approval_status', 'approval_message']);
        });
    }
};
