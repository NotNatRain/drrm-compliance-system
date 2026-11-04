<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE `schools` MODIFY `cluster_type` tinyint(1) NULL DEFAULT 0;");
        DB::statement("ALTER TABLE `schools` MODIFY `noncluster_type` tinyint(1) NULL DEFAULT 0;");
    }

    public function down(): void
    {
        // Keep columns nullable with default 0 to prevent data insertion failures
    }
};
