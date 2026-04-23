<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const AY_START_MONTH = 6;

    private function deriveAcademicYear(?string $dateValue): string
    {
        try {
            $date = $dateValue ? Carbon::parse($dateValue) : now();
        } catch (\Throwable $e) {
            $date = now();
        }

        $year = (int) $date->year;
        $startYear = (int) ($date->month >= self::AY_START_MONTH ? $year : ($year - 1));

        return $startYear . '-' . ($startYear + 1);
    }

    public function up(): void
    {
        if (Schema::hasTable('cmpr_schl_sfty_assessments') && !Schema::hasColumn('cmpr_schl_sfty_assessments', 'academic_year')) {
            Schema::table('cmpr_schl_sfty_assessments', function (Blueprint $table) {
                $table->string('academic_year', 9)->nullable()->after('status');
                $table->index(['school_id', 'academic_year'], 'cmpr_assess_school_academic_year_idx');
            });

            DB::table('cmpr_schl_sfty_assessments')
                ->orderBy('id')
                ->select(['id', 'date_visited', 'created_at'])
                ->chunkById(200, function ($rows) {
                    foreach ($rows as $row) {
                        $academicYear = $this->deriveAcademicYear((string) ($row->date_visited ?? $row->created_at));
                        DB::table('cmpr_schl_sfty_assessments')
                            ->where('id', $row->id)
                            ->update(['academic_year' => $academicYear]);
                    }
                });
        }

        if (!Schema::hasTable('cmpr_schl_sfty_archives')) {
            Schema::create('cmpr_schl_sfty_archives', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
                $table->string('archive_type', 40);
                $table->string('academic_year', 9);
                $table->json('payload')->nullable();
                $table->timestamp('archived_at')->nullable();
                $table->timestamps();

                $table->unique(['school_id', 'archive_type', 'academic_year'], 'cmpr_archives_unique_school_type_year');
                $table->index(['school_id', 'archived_at'], 'cmpr_archives_school_archived_at_idx');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('cmpr_schl_sfty_assessments') && Schema::hasColumn('cmpr_schl_sfty_assessments', 'academic_year')) {
            Schema::table('cmpr_schl_sfty_assessments', function (Blueprint $table) {
                if (Schema::hasColumn('cmpr_schl_sfty_assessments', 'academic_year')) {
                    $table->dropIndex('cmpr_assess_school_academic_year_idx');
                    $table->dropColumn('academic_year');
                }
            });
        }

        if (Schema::hasTable('cmpr_schl_sfty_archives')) {
            Schema::dropIfExists('cmpr_schl_sfty_archives');
        }
    }
};
