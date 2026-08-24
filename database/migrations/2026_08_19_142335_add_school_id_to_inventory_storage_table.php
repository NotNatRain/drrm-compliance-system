<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\School;

return new class extends Migration
{
    public function up(): void
    {
        // First, let's get the first school id to assign to existing records
        $firstSchool = School::first();
        $defaultSchoolId = $firstSchool ? $firstSchool->id : 1;

        Schema::table('inventory_storage', function (Blueprint $table) {
            $table->foreignId('school_id')->nullable()->after('id')->constrained('schools')->onDelete('cascade');
        });

        // Update existing records
        DB::table('inventory_storage')->update(['school_id' => $defaultSchoolId]);

        // Make it non-nullable now
        Schema::table('inventory_storage', function (Blueprint $table) {
            $table->foreignId('school_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('inventory_storage', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn('school_id');
        });
    }
};
