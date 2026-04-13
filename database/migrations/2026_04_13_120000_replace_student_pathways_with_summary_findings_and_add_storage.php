<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cmpr_schl_sfty_student_pathways')) {
            Schema::drop('cmpr_schl_sfty_student_pathways');
        }

        if (!Schema::hasTable('cmpr_schl_sfty_sumFindings')) {
            Schema::create('cmpr_schl_sfty_sumFindings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
                $table->foreignId('building_id')->constrained('firesafety_buildings')->cascadeOnDelete();
                $table->string('concern_category');
                $table->string('concern_type');
                $table->text('description');
                $table->string('priority', 20)->default('medium');
                $table->date('observation_date');
                $table->text('remarks')->nullable();
                $table->timestamps();

                $table->index(['school_id', 'building_id']);
                $table->index(['concern_category']);
                $table->index(['concern_type']);
            });
        }

        if (!Schema::hasTable('cmpr_schl_sfty_storage')) {
            Schema::create('cmpr_schl_sfty_storage', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
                $table->string('item_name');
                $table->string('item_type')->nullable();
                $table->boolean('is_available')->default(false);
                $table->boolean('is_functional')->default(false);
                $table->text('remarks')->nullable();
                $table->timestamps();

                $table->index(['school_id']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('cmpr_schl_sfty_storage')) {
            Schema::drop('cmpr_schl_sfty_storage');
        }

        if (Schema::hasTable('cmpr_schl_sfty_sumFindings')) {
            Schema::drop('cmpr_schl_sfty_sumFindings');
        }

        if (!Schema::hasTable('cmpr_schl_sfty_student_pathways')) {
            Schema::create('cmpr_schl_sfty_student_pathways', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('cmpr_schl_sfty_students')->cascadeOnDelete();
                $table->integer('pathway_score');
                $table->date('observation_date');
                $table->text('remarks')->nullable();
                $table->timestamps();
            });
        }
    }
};
