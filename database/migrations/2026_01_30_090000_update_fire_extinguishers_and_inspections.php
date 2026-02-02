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
        Schema::table('firesafety_fire_extinguishers', function (Blueprint $table) {
            if (!Schema::hasColumn('firesafety_fire_extinguishers', 'type')) {
                $table->string('type')->nullable()->after('code'); // ABC, CO2, etc.
            }
            if (!Schema::hasColumn('firesafety_fire_extinguishers', 'pressure_level')) {
                $table->integer('pressure_level')->default(100)->after('status'); // 0-100
            }
        });

        if (!Schema::hasTable('fire_safety_extinguisher_inspections')) {
            Schema::create('fire_safety_extinguisher_inspections', function (Blueprint $table) {
                $table->id();
                $table->foreignId('extinguisher_id')->constrained('firesafety_fire_extinguishers')->onDelete('cascade');
                $table->unsignedBigInteger('user_id')->nullable(); // Inspector
                $table->date('inspection_date');
                $table->string('status'); // active, expired, maintenance, etc.
                $table->string('pressure_level')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fire_safety_extinguisher_inspections');

        Schema::table('firesafety_fire_extinguishers', function (Blueprint $table) {
            $table->dropColumn(['type', 'pressure_level']);
        });
    }
};
