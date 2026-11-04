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
        if (!Schema::hasTable('heat_index_reports')) {
            Schema::create('heat_index_reports', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('school_id')->nullable()->index();
                $table->string('school_name');
                $table->string('school_head')->nullable();
                $table->string('submitted_by')->nullable();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->date('report_date');
                $table->string('report_time', 20)->nullable();
                $table->decimal('temperature', 4, 1);
                $table->integer('humidity');
                $table->decimal('heat_index', 4, 1);
                $table->string('category', 50); // Caution, Extreme Caution, Danger, Extreme Danger
                $table->string('badge_color', 20)->nullable();
                $table->string('action_taken')->nullable();
                $table->text('remarks')->nullable();
                $table->timestamps();

                $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('heat_index_reports');
    }
};
