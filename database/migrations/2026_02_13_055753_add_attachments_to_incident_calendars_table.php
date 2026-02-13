<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('incident_calendars', function (Blueprint $table) {
            $table->string('attachment_path')->nullable()->after('remarks');
            $table->string('attachment_name')->nullable()->after('attachment_path');
            $table->unsignedBigInteger('attachment_size')->nullable()->after('attachment_name');
            $table->string('attachment_mime')->nullable()->after('attachment_size');
        });
    }

    public function down()
    {
        Schema::table('incident_calendars', function (Blueprint $table) {
            $table->dropColumn(['attachment_path', 'attachment_name', 'attachment_size', 'attachment_mime']);
        });
    }
};
