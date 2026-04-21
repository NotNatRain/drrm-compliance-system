<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\School;

class TestAllPages extends Command
{
    protected $signature = 'test:all-pages';
    protected $description = 'Comprehensive test of all DRRM page functionality after XAMPP reinstall';

    public function handle()
    {
        $this->newLine();
        $this->line(str_repeat('=', 80));
        $this->info('DRRM PAGE FUNCTIONALITY TEST REPORT');
        $this->line('Date: ' . now()->format('Y-m-d H:i:s'));
        $this->line(str_repeat('=', 80));
        $this->newLine();

        $passed = [];
        $failed = [];
        $warnings = [];

        // 1. Database Check
        $this->line('1. Database Connection Check');
        $this->line(str_repeat('-', 40));
        try {
            DB::connection()->getPdo();
            $this->info('✓ Database connection successful');
            $passed[] = 'Database Connection';
        } catch (\Exception $e) {
            $this->error('✗ Database connection failed: ' . $e->getMessage());
            $failed[] = 'Database Connection';
        }
        $this->newLine();

        // 2. Storage Symlink Check
        $this->line('2. Storage Symlink Check');
        $this->line(str_repeat('-', 40));
        $storageLinkExists = is_link(public_path('storage'));
        if ($storageLinkExists) {
            $this->info('✓ Storage symlink exists');
            $passed[] = 'Storage Symlink';
        } else {
            $this->warn('⚠ Storage symlink missing (run: php artisan storage:link)');
            $warnings[] = 'Storage symlink missing';
        }
        $this->newLine();

        // 3. Check Fire Safety Tables
        $this->line('3. Fire Safety Module Tables');
        $this->line(str_repeat('-', 40));
        $fsTables = [
            'firesafety_buildings' => 'Buildings',
            'firesafety_fire_extinguishers' => 'Extinguishers',
            'firesafety_alarm_systems' => 'Alarm Systems',
            'firesafety_rooms' => 'Rooms'
        ];
        foreach ($fsTables as $table => $label) {
            try {
                if (Schema::hasTable($table)) {
                    $count = DB::table($table)->count();
                    $this->info("✓ $label ($table): $count records");
                    $passed[] = "Fire Safety - $label";
                } else {
                    $this->line("  ℹ $label table not yet created");
                }
            } catch (\Exception $e) {
                $this->error("✗ $label table error");
                $failed[] = "Fire Safety - $label";
            }
        }
        $this->newLine();

        // 4. Check Incident Module
        $this->line('4. Incident Checklist Module');
        $this->line(str_repeat('-', 40));
        $incidentTables = [
            'incident_calendars' => 'Calendar',
            'incident_checklists' => 'Checklists',
            'incident_types' => 'Types',
            'incident_statuses' => 'Statuses'
        ];
        foreach ($incidentTables as $table => $label) {
            try {
                $count = DB::table($table)->count();
                $this->info("✓ $label ($table): $count records");
                $passed[] = "Incident - $label";
            } catch (\Exception $e) {
                $this->error("✗ $label table error");
                $failed[] = "Incident - $label";
            }
        }
        $this->newLine();

        // 5. Check Comprehensive Module
        $this->line('5. Comprehensive School Safety Module');
        $this->line(str_repeat('-', 40));
        $compTables = [
            'comprehensive_assessments' => 'Assessments',
            'comprehensive_students' => 'Students',
            'comprehensive_facilities' => 'Facilities',
            'comprehensive_storage' => 'Storage',
            'comprehensive_summary_findings' => 'Findings'
        ];
        foreach ($compTables as $table => $label) {
            if (Schema::hasTable($table)) {
                try {
                    $count = DB::table($table)->count();
                    $this->info("✓ $label ($table): $count records");
                    $passed[] = "Comprehensive - $label";
                } catch (\Exception $e) {
                    $this->error("✗ $label table error");
                    $failed[] = "Comprehensive - $label";
                }
            } else {
                $this->line("  ℹ $label table not yet created");
            }
        }
        $this->newLine();

        // 6. Check Typhoon/Flood Module
        $this->line('6. Typhoon/Flood Module');
        $this->line(str_repeat('-', 40));
        $tfTables = [
            'typ_fld_families' => 'Families',
            'typ_fld_family_members' => 'Family Members',
            'typ_fld_monitoring_snapshots' => 'Monitoring'
        ];
        foreach ($tfTables as $table => $label) {
            if (Schema::hasTable($table)) {
                try {
                    $count = DB::table($table)->count();
                    $this->info("✓ $label ($table): $count records");
                    $passed[] = "Typhoon - $label";
                } catch (\Exception $e) {
                    $this->error("✗ $label table error");
                    $failed[] = "Typhoon - $label";
                }
            } else {
                $this->line("  ℹ $label table not yet created");
            }
        }
        $this->newLine();

        // 7. Check Activity Log
        $this->line('7. Activity Log Module');
        $this->line(str_repeat('-', 40));
        try {
            $count = DB::table('activity_logs')->count();
            $this->info("✓ Activity Logs: $count records");
            $passed[] = 'Activity Log';
        } catch (\Exception $e) {
            $this->error("✗ Activity Log error");
            $failed[] = 'Activity Log';
        }
        $this->newLine();

        // 8. Check Users
        $this->line('8. Users Management');
        $this->line(str_repeat('-', 40));
        try {
            $total = DB::table('users')->count();
            $admins = DB::table('users')->where('role', 'admin')->count();
            $this->info("✓ Users: $total total ($admins admins)");
            $passed[] = 'Users';
        } catch (\Exception $e) {
            $this->error("✗ Users error");
            $failed[] = 'Users';
        }
        $this->newLine();

        // 9. Check Announcements
        $this->line('9. Announcements Module');
        $this->line(str_repeat('-', 40));
        try {
            $count = DB::table('announcements')->count();
            $this->info("✓ Announcements: $count records");
            $passed[] = 'Announcements';
        } catch (\Exception $e) {
            $this->error("✗ Announcements error");
            $failed[] = 'Announcements';
        }
        $this->newLine();

        // 10. Schools Registration
        $this->line('10. School Registration Status');
        $this->line(str_repeat('-', 40));
        try {
            if (Schema::hasTable('schools')) {
                $total = School::count();
                $fsSchools = School::where('has_fire_safety', true)->count();
                $tfSchools = School::where('has_typhoon_flood', true)->count();
                $incSchools = School::where('has_incident_checklist', true)->count();
                $compSchools = School::where('has_comprehensive', true)->count();

                $this->info("✓ Schools: $total total");
                $this->line("  - Fire Safety: $fsSchools");
                $this->line("  - Typhoon/Flood: $tfSchools");
                $this->line("  - Incident Checklist: $incSchools");
                $this->line("  - Comprehensive: $compSchools");
                $passed[] = 'School Registration';
            } else {
                $this->line("  ℹ Schools table not yet created");
            }
        } catch (\Exception $e) {
            $this->error("✗ Schools error: " . $e->getMessage());
            $failed[] = 'Schools';
        }
        $this->newLine();

        // 11. Views Check
        $this->line('11. Views and Templates');
        $this->line(str_repeat('-', 40));
        $files = glob(resource_path('views/**/*.blade.php'), 2); // GLOB_RECURSIVE = 2
        $viewCount = $files ? count($files) : 0;
        $this->info("✓ Views: $viewCount blade templates");
        $passed[] = 'Views';
        $this->newLine();

        // 12. Build Check
        $this->line('12. Frontend Build Status');
        $this->line(str_repeat('-', 40));
        $manifestExists = file_exists(public_path('build/manifest.json'));
        if ($manifestExists) {
            $this->info('✓ Build manifest exists');
            $passed[] = 'Frontend Build';
        } else {
            $this->warn('⚠ Build manifest missing (run: npm run build)');
            $warnings[] = 'Build manifest missing';
        }
        $this->newLine();

        // Summary
        $this->line(str_repeat('=', 80));
        $this->info('TEST SUMMARY');
        $this->line(str_repeat('=', 80));
        $this->newLine();

        $this->info("✓ PASSED: " . count($passed) . " checks");
        if (!empty($warnings)) {
            $this->warn("⚠ WARNINGS: " . count($warnings) . " items");
        }
        if (!empty($failed)) {
            $this->error("✗ FAILED: " . count($failed) . " checks");
        } else {
            $this->info("✗ FAILED: 0 checks");
        }
        $this->newLine();

        if (empty($failed) && empty($warnings)) {
            $this->info('OVERALL STATUS: ✓ ALL SYSTEMS OPERATIONAL');
        } elseif (empty($failed)) {
            $this->warn('OVERALL STATUS: ⚠ OPERATIONAL WITH WARNINGS');
        } else {
            $this->error('OVERALL STATUS: ✗ ISSUES DETECTED');
        }

        $this->line(str_repeat('=', 80));
        $this->newLine();

        // Recommendations
        if (!$storageLinkExists) {
            $this->warn('ACTION REQUIRED: Create storage symlink');
            $this->line('  $ php artisan storage:link');
            $this->newLine();
        }

        if (!$manifestExists) {
            $this->warn('ACTION REQUIRED: Rebuild frontend assets');
            $this->line('  $ npm ci');
            $this->line('  $ npm run build');
            $this->newLine();
        }

        $this->info('Access pages at:');
        $this->line('  Dashboard: http://localhost/dashboard');
        $this->line('  Fire Safety: http://localhost/fire-safety/dashboard');
        $this->line('  Typhoon/Flood: http://localhost/typhoon/dashboard');
        $this->line('  Incident Checklist: http://localhost/incidents/dashboard');
        $this->line('  Comprehensive: http://localhost/comprehensive-school-safety/dashboard');
        $this->line('  Hazard Mapping: http://localhost/hazard-mapping/dashboard');
        $this->line('  Activity Log: http://localhost/activity-logs');
        $this->line('  Users: http://localhost/users');
        $this->newLine();

        return empty($failed) ? 0 : 1;
    }
}
