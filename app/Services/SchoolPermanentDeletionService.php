<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\IncidentCalendar;
use App\Models\School;
use App\Models\TypFldFamily;
use App\Models\TypFldMonitoringSnapshot;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

/**
 * Permanently removes a school and all module data that references it.
 * Intended for admin cleanup (example schools, etc.).
 */
class SchoolPermanentDeletionService
{
    public function deletePermanently(School $school): void
    {
        $id = $school->id;
        $name = $school->school_name;

        DB::transaction(function () use ($school, $id, $name) {
            $this->detachUsers($id);
            $this->deleteSchoolUploadedFiles($school);
            $this->deleteIncidentCalendarRowsAndFiles($id, $name);
            $this->deleteActivityLogs($id);
            $this->deleteNotifications($id);
            $this->deleteTyphoonRows($id);
            $this->deleteComprehensiveModuleRows($id);

            $school->delete();
        });
    }

    private function deleteSchoolUploadedFiles(School $school): void
    {
        $path = $school->attached_evacuation_map;
        if (! empty($path) && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function detachUsers(int $schoolId): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        User::query()->where(function ($q) use ($schoolId) {
            $q->where('school_id', $schoolId)
                ->orWhere('incident_school_id', $schoolId)
                ->orWhere('typhoon_school_id', $schoolId);
        })->update([
            'school_id' => null,
            'incident_school_id' => null,
            'typhoon_school_id' => null,
        ]);
    }

    private function deleteIncidentCalendarRowsAndFiles(int $schoolId, string $schoolName): void
    {
        if (! Schema::hasTable('incident_calendars')) {
            return;
        }

        $rows = IncidentCalendar::query()
            ->where(function ($q) use ($schoolId, $schoolName) {
                $q->where('school_id', $schoolId);
                if ($schoolName !== '') {
                    $q->orWhere('school_name', $schoolName);
                }
            })
            ->get();

        foreach ($rows as $row) {
            if (! empty($row->attachment_path) && Storage::disk('public')->exists($row->attachment_path)) {
                Storage::disk('public')->delete($row->attachment_path);
            }
        }

        if ($rows->isNotEmpty()) {
            IncidentCalendar::query()->whereIn('id', $rows->pluck('id'))->delete();
        }
    }

    private function deleteActivityLogs(int $schoolId): void
    {
        if (! Schema::hasTable('activity_logs')) {
            return;
        }

        ActivityLog::query()->where('school_id', $schoolId)->delete();
    }

    private function deleteNotifications(int $schoolId): void
    {
        if (! Schema::hasTable('notifications')) {
            return;
        }

        DB::table('notifications')->where('school_id', $schoolId)->delete();
    }

    private function deleteTyphoonRows(int $schoolId): void
    {
        if (Schema::hasTable('typ_fld_families')) {
            TypFldFamily::query()->where('school_id', $schoolId)->delete();
        }
        if (Schema::hasTable('typ_fld_monitoring_snapshots')) {
            TypFldMonitoringSnapshot::query()->where('school_id', $schoolId)->delete();
        }
    }

    /**
     * Comprehensive School Safety (cmpr_*) — delete children explicitly so nothing blocks school removal.
     */
    private function deleteComprehensiveModuleRows(int $schoolId): void
    {
        if (! Schema::hasTable('cmpr_schl_sfty_assessments')) {
            return;
        }

        $assessmentIds = DB::table('cmpr_schl_sfty_assessments')
            ->where('school_id', $schoolId)
            ->pluck('id');

        if ($assessmentIds->isNotEmpty()) {
            if (Schema::hasTable('cmpr_schl_sfty_assessment_items')) {
                DB::table('cmpr_schl_sfty_assessment_items')
                    ->whereIn('assessment_id', $assessmentIds)
                    ->delete();
            }
            DB::table('cmpr_schl_sfty_assessments')
                ->whereIn('id', $assessmentIds)
                ->delete();
        }

        if (Schema::hasTable('cmpr_schl_sfty_students')) {
            $studentIds = DB::table('cmpr_schl_sfty_students')
                ->where('school_id', $schoolId)
                ->pluck('id');
            if ($studentIds->isNotEmpty() && Schema::hasTable('cmpr_schl_sfty_student_pathways')) {
                DB::table('cmpr_schl_sfty_student_pathways')
                    ->whereIn('student_id', $studentIds)
                    ->delete();
            }
            DB::table('cmpr_schl_sfty_students')->where('school_id', $schoolId)->delete();
        }

        if (Schema::hasTable('cmpr_schl_sfty_facilities')) {
            DB::table('cmpr_schl_sfty_facilities')->where('school_id', $schoolId)->delete();
        }
    }
}
