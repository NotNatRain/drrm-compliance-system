<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pie_pra_volunteers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact');
            $table->string('barangay')->nullable();
            $table->string('qr_token')->unique();
            $table->string('status')->default('available'); // available|on-duty|inactive
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pie_pra_volunteers');
    }
};

