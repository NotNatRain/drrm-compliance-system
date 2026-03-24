<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cmpr_schl_sfty_schools', function (Blueprint $table) {
            $table->id();
            $table->string('school_id_number')->unique()->nullable();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('district')->nullable();
            $table->string('division')->nullable();
            $table->string('region')->nullable();
            $table->string('school_head')->nullable();
            $table->string('contact_number')->nullable();
            $table->timestamps();
        });

        Schema::create('cmpr_schl_sfty_facilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('cmpr_schl_sfty_schools')->cascadeOnDelete();
            $table->string('type'); // room, door, pathway, etc.
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('condition')->default('good'); // good, fair, needs_repair, condemned
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('cmpr_schl_sfty_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('cmpr_schl_sfty_schools')->cascadeOnDelete();
            $table->string('student_lrn')->unique()->nullable();
            $table->string('name');
            $table->string('grade_level')->nullable();
            $table->string('section')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_contact')->nullable();
            $table->timestamps();
        });

        Schema::create('cmpr_schl_sfty_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('cmpr_schl_sfty_schools')->cascadeOnDelete();
            $table->date('date_visited');
            $table->string('assessed_by')->nullable();
            $table->decimal('total_score', 8, 2)->default(0);
            $table->string('status')->default('draft');
            $table->timestamps();
        });

        Schema::create('cmpr_schl_sfty_assessment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('cmpr_schl_sfty_assessments')->cascadeOnDelete();
            $table->string('category'); // Enabling Environment, etc.
            $table->text('criteria');
            $table->boolean('is_compliant')->nullable(); // Yes/No
            $table->integer('points')->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('cmpr_schl_sfty_student_pathways', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('cmpr_schl_sfty_students')->cascadeOnDelete();
            $table->integer('pathway_score'); // 0-10
            $table->date('observation_date');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cmpr_schl_sfty_student_pathways');
        Schema::dropIfExists('cmpr_schl_sfty_assessment_items');
        Schema::dropIfExists('cmpr_schl_sfty_assessments');
        Schema::dropIfExists('cmpr_schl_sfty_students');
        Schema::dropIfExists('cmpr_schl_sfty_facilities');
        Schema::dropIfExists('cmpr_schl_sfty_schools');
    }
};
