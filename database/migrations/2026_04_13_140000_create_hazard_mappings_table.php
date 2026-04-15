<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hzd_map_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->integer('floor_number')->default(1);
            $table->string('floor_name')->nullable();
            $table->json('hazards')->nullable()->comment('Array of hazard items');
            $table->json('vulnerabilities')->nullable()->comment('Array of vulnerability items');
            $table->json('evacuation_routes')->nullable()->comment('Array of evacuation route coordinates');
            $table->json('assembly_points')->nullable()->comment('Array of assembly point locations');
            $table->json('safe_zones')->nullable()->comment('Array of safe zone coordinates');
            $table->json('hazard_zones')->nullable()->comment('Array of hazard zone coordinates');
            $table->text('notes')->nullable();
            $table->json('map_data')->nullable()->comment('Complete map rendering data including coordinates, dimensions');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->index(['school_id', 'floor_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hzd_map_info');
    }
};
