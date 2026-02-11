<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('firesafety_school_snapshots', function (Blueprint $table) {
            $table->id();
            $table->string('school_id_code')->index(); // Original school_id
            $table->string('school_name');
            $table->json('full_data'); // The huge snapshot
            $table->string('deleted_by')->nullable();
            $table->text('reason')->nullable();
            $table->timestamp('deleted_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('firesafety_school_snapshots');
    }
};
