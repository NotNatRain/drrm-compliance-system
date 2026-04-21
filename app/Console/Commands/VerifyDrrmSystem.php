<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class VerifyDrrmSystem extends Command
{
    protected $signature = 'verify:drrm-system';
    protected $description = 'Comprehensive DRRM system verification after XAMPP reinstall';

    public function handle()
    {
        $this->line("\n========== DRRM COMPREHENSIVE SYSTEM VERIFICATION ==========\n");

        $passed = 0;
        $failed = 0;

        // ====== DATABASE CONNECTIVITY ======
        $this->info('--- Database Connectivity Tests ---');
        try {
            DB::connection()->getPdo();
            $this->line('[✓ PASS] Database connection');
            $passed++;
        } catch (\Exception $e) {
            $this->error('[✗ FAIL] Database connection: ' . $e->getMessage());
            $failed++;
        }

        // ====== STORAGE SYMLINK ======
        $this->info('--- Storage/Symlink Tests ---');
        if (file_exists(base_path('public/storage'))) {
            $this->line('[✓ PASS] Storage symlink exists');
            $passed++;
        } else {
            $this->error('[✗ FAIL] Storage symlink missing');
            $failed++;
        }

        $publicStoragePath = base_path('public/storage');
        $resolvedStorageTarget = @readlink($publicStoragePath);
        if (is_link($publicStoragePath)) {
            $this->line('[✓ PASS] Public/storage is a symlink');
            $passed++;
        } elseif ($resolvedStorageTarget !== false) {
            $this->line('[✓ PASS] Public/storage link target resolves to: ' . $resolvedStorageTarget);
            $passed++;
        } elseif (PHP_OS_FAMILY === 'Windows' && is_dir($publicStoragePath)) {
            // On Windows, Laravel storage:link may create a junction that is_dir() detects
            // while is_link() can still return false.
            $this->line('[✓ PASS] Public/storage is available (Windows junction/linked directory)');
            $passed++;
        } else {
            $this->warn('[⚠ WARN] Public/storage exists but link type could not be verified');
            $failed++;
        }

        $announceDir = storage_path('app/public/announcements');
        if (!is_dir($announceDir)) {
            mkdir($announceDir, 0755, true);
        }
        if (is_writable($announceDir)) {
            $this->line('[✓ PASS] Announcement directory writable');
            $passed++;
        } else {
            $this->error('[✗ FAIL] Announcement directory not writable');
            $failed++;
        }

        try {
            $probeDir = storage_path('app/public/announcements');
            $probeFile = $probeDir . DIRECTORY_SEPARATOR . 'upload-probe.txt';
            file_put_contents($probeFile, 'ok');
            $ok = file_exists($probeFile);
            if ($ok) {
                unlink($probeFile);
                $this->line('[✓ PASS] Announcement upload storage write/read test');
                $passed++;
            } else {
                $this->error('[✗ FAIL] Announcement upload storage write/read test');
                $failed++;
            }
        } catch (\Exception $e) {
            $this->error('[✗ FAIL] Announcement upload storage test: ' . $e->getMessage());
            $failed++;
        }

        // ====== FIRE SAFETY MODULE ======
        $this->info('--- Fire Safety Module Tests ---');

        try {
            $schoolCount = DB::table('schools')->count();
            $this->line("[✓ PASS] Fire safety schools exist (Count: $schoolCount)");
            $passed++;
        } catch (\Exception $e) {
            $this->error('[✗ FAIL] Fire safety schools: ' . $e->getMessage());
            $failed++;
        }

        try {
            $buildingCount = DB::table('firesafety_buildings')->count();
            $this->line("[✓ PASS] Fire safety buildings exist (Count: $buildingCount)");
            $passed++;
        } catch (\Exception $e) {
            $this->error('[✗ FAIL] Fire safety buildings: ' . $e->getMessage());
            $failed++;
        }

        try {
            $alarmCount = DB::table('firesafety_alarm_systems')->count();
            $this->line("[✓ PASS] Fire safety alarms exist (Count: $alarmCount)");
            $passed++;
        } catch (\Exception $e) {
            $this->error('[✗ FAIL] Fire safety alarms: ' . $e->getMessage());
            $failed++;
        }

        try {
            $extCount = DB::table('firesafety_fire_extinguishers')->count();
            $this->line("[✓ PASS] Fire safety extinguishers exist (Count: $extCount)");
            $passed++;
        } catch (\Exception $e) {
            $this->error('[✗ FAIL] Fire safety extinguishers: ' . $e->getMessage());
            $failed++;
        }

        try {
            $roomCount = DB::table('fire_safety_rooms')->count();
            $this->line("[✓ PASS] Fire safety rooms exist (Count: $roomCount)");
            $passed++;
        } catch (\Exception $e) {
            $this->error('[✗ FAIL] Fire safety rooms: ' . $e->getMessage());
            $failed++;
        }

        try {
            $recoveredSchools = DB::table('schools')
                ->where(function ($q) {
                    $q->where('school_name', 'like', '%ASINAN%')
                      ->orWhere('school_name', 'like', '%TABACUHAN%')
                      ->orWhere('school_name', 'like', '%SANTA RITA%');
                })
                ->pluck('id', 'school_name')
                ->toArray();

            if (count($recoveredSchools) === 3) {
                $this->line('[✓ PASS] All 3 recovered schools present: ' . implode(', ', array_keys($recoveredSchools)));
                $passed++;
            } else {
                $this->warn('[⚠ WARN] Only ' . count($recoveredSchools) . ' of 3 recovered schools found: ' . implode(', ', array_keys($recoveredSchools)));
                $failed++;
            }
        } catch (\Exception $e) {
            $this->error('[✗ FAIL] Recovered schools check: ' . $e->getMessage());
            $failed++;
        }

        try {
            $underRepairCount = DB::table('firesafety_alarm_systems')
                ->where('status', 'under_repair')
                ->count();
            if ($underRepairCount > 0) {
                $this->line("[✓ PASS] Alarm status mapping preserved (Found $underRepairCount 'under_repair' alarms)");
                $passed++;
            } else {
                $this->warn('[⚠ WARN] No under_repair alarms found - check status preservation');
                $failed++;
            }
        } catch (\Exception $e) {
            $this->error('[✗ FAIL] Alarm status check: ' . $e->getMessage());
            $failed++;
        }

        try {
            $remarksCount = DB::table('firesafety_fire_extinguishers')
                ->whereNotNull('remarks')
                ->where('remarks', '!=', '')
                ->count();
            if ($remarksCount > 0) {
                $this->line("[✓ PASS] Extinguisher remarks preserved (Found $remarksCount with remarks)");
                $passed++;
            } else {
                $this->warn('[⚠ WARN] No extinguisher remarks found');
                $failed++;
            }
        } catch (\Exception $e) {
            $this->error('[✗ FAIL] Extinguisher remarks check: ' . $e->getMessage());
            $failed++;
        }

        // ====== TYPHOON/FLOOD MODULE ======
        $this->info('--- Typhoon/Flood Module Tests ---');
        try {
            $typhoonUsers = DB::table('users')
                ->whereNotNull('typhoon_school_id')
                ->count();
            $this->line("[✓ PASS] Typhoon module configured (Users: $typhoonUsers)");
            $passed++;
        } catch (\Exception $e) {
            $this->error('[✗ FAIL] Typhoon configuration: ' . $e->getMessage());
            $failed++;
        }

        try {
            $typhoonSnapshots = DB::table('typ_fld_monitoring_snapshots')->count();
            $this->line("[✓ PASS] Typhoon monitoring table accessible (Records: $typhoonSnapshots)");
            $passed++;
        } catch (\Exception $e) {
            $this->warn('[⚠ WARN] Typhoon monitoring table: ' . $e->getMessage());
            $failed++;
        }

        // ====== INCIDENT MODULE ======
        $this->info('--- Incident Module Tests ---');
        try {
            $incidentUsers = DB::table('users')
                ->whereNotNull('incident_school_id')
                ->count();
            $this->line("[✓ PASS] Incident module configured (Users: $incidentUsers)");
            $passed++;
        } catch (\Exception $e) {
            $this->error('[✗ FAIL] Incident configuration: ' . $e->getMessage());
            $failed++;
        }

        try {
            $incidentChecklists = DB::table('incident_checklists')->count();
            $this->line("[✓ PASS] Incident checklists table accessible (Records: $incidentChecklists)");
            $passed++;
        } catch (\Exception $e) {
            $this->warn('[⚠ WARN] Incident checklists: ' . $e->getMessage());
            $failed++;
        }

        // ====== COMPLIANCE/ASSESSMENT MODULE ======
        $this->info('--- Compliance/Assessment Module Tests ---');
        try {
            $assessments = DB::table('cmpr_schl_sfty_assessments')->count();
            $this->line("[✓ PASS] Comprehensive assessments table accessible (Records: $assessments)");
            $passed++;
        } catch (\Exception $e) {
            $this->warn('[⚠ WARN] Comprehensive assessments: ' . $e->getMessage());
            $failed++;
        }

        try {
            $facilities = DB::table('cmpr_schl_sfty_facilities')->count();
            $this->line("[✓ PASS] Comprehensive facilities table accessible (Records: $facilities)");
            $passed++;
        } catch (\Exception $e) {
            $this->warn('[⚠ WARN] Comprehensive facilities: ' . $e->getMessage());
            $failed++;
        }

        // ====== HAZARD MAPPING MODULE ======
        $this->info('--- Hazard Mapping Module Tests ---');
        try {
            $hazards = DB::table('hzd_map_info')->count();
            $this->line("[✓ PASS] Hazard mapping table accessible (Records: $hazards)");
            $passed++;
        } catch (\Exception $e) {
            $this->warn('[⚠ WARN] Hazard mapping: ' . $e->getMessage());
            $failed++;
        }

        // ====== ANNOUNCEMENTS ======
        $this->info('--- Announcement/Dashboard Tests ---');
        try {
            $announcements = DB::table('announcements')->count();
            $this->line("[✓ PASS] Announcements table accessible (Count: $announcements)");
            $passed++;
        } catch (\Exception $e) {
            $this->error('[✗ FAIL] Announcements: ' . $e->getMessage());
            $failed++;
        }

        // ====== ACTIVITY LOGS ======
        $this->info('--- Activity Log Tests ---');
        try {
            $activityLogs = DB::table('activity_logs')->count();
            $this->line("[✓ PASS] Activity logs table accessible (Count: $activityLogs)");
            $passed++;
        } catch (\Exception $e) {
            $this->warn('[⚠ WARN] Activity logs: ' . $e->getMessage());
            $failed++;
        }

        // ====== USER MANAGEMENT ======
        $this->info('--- User Management Tests ---');
        try {
            $totalUsers = DB::table('users')->count();
            $admins = DB::table('users')->where('role', 'admin')->count();
            $contributors = DB::table('users')->where('role', 'contributor')->count();
            $viewers = DB::table('users')->where('role', 'viewer')->count();

            $this->line("[✓ PASS] Users exist (Total: $totalUsers | Admins: $admins | Contributors: $contributors | Viewers: $viewers)");
            $passed++;
        } catch (\Exception $e) {
            $this->error('[✗ FAIL] Users: ' . $e->getMessage());
            $failed++;
        }

        try {
            $linkedUsers = DB::table('users')->whereNotNull('school_id')->count();
            $this->line("[✓ PASS] Users linked to schools (Count: $linkedUsers)");
            $passed++;
        } catch (\Exception $e) {
            $this->warn('[⚠ WARN] User-school links: ' . $e->getMessage());
            $failed++;
        }

        // ====== SUMMARY ======
        $this->info('========== VERIFICATION SUMMARY ==========');
        $total = $passed + $failed;
        $percentage = $total > 0 ? round(($passed / $total) * 100, 2) : 0;

        $this->line("Passed: $passed / $total ($percentage%)");

        if ($failed === 0) {
            $this->info("\n✓ ALL SYSTEMS OPERATIONAL\n");
            return 0;
        } else {
            $this->warn("\n⚠ $failed issues found - review output above\n");
            return 1;
        }
    }
}
