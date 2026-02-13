<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('typ_fld_family_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('family_id');

            $table->string('full_name');
            $table->unsignedSmallInteger('age');
            $table->enum('gender', ['male', 'female']);
            $table->string('needs')->nullable();

            $table->boolean('is_head')->default(false);

            // casualty tracking per individual
            $table->enum('status', ['normal', 'missing', 'injured', 'deceased'])->default('normal');

            $table->timestamps();

            $table->foreign('family_id')
                ->references('id')
                ->on('typ_fld_families')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('typ_fld_family_members');
    }
};

