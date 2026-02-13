<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('typ_fld_families', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evacuation_center_id');

            $table->string('head_family_name');
            $table->text('collective_needs')->nullable();

            // family special concerns
            $table->boolean('has_pregnant')->default(false);
            $table->boolean('has_pwd')->default(false);
            $table->boolean('has_senior')->default(false);
            $table->boolean('has_lactating')->default(false);
            $table->boolean('has_child_under5')->default(false);

            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('checked_out_at')->nullable();

            $table->timestamps();

            $table->foreign('evacuation_center_id')
                ->references('id')
                ->on('typ_fld_evacuation_centers')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('typ_fld_families');
    }
};

