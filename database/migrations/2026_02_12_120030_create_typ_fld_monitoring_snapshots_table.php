<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('typ_fld_monitoring_snapshots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evacuation_center_id');

            $table->string('type'); // flood|typhoon|routes
            $table->json('payload')->nullable();
            $table->timestamp('recorded_at')->nullable();

            $table->timestamps();

            $table->index(['evacuation_center_id', 'type']);

            $table->foreign('evacuation_center_id')
                ->references('id')
                ->on('typ_fld_evacuation_centers')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('typ_fld_monitoring_snapshots');
    }
};

