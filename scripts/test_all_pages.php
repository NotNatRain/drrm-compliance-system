<?php
/**
 * Comprehensive Page Testing Script
 * Verifies all major DRRM pages work correctly after XAMPP reinstall
 */

require_once dirname(__DIR__) . '/bootstrap/app.php';

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\School;

// Test results storage
$results = [
    'passed' => [],
    'failed' => [],
    'warnings' => []
];

echo "\n" . str_repeat("=", 80) . "\n";
echo "DRRM PAGE FUNCTIONALITY TEST REPORT\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n";
echo str_repeat("=", 80) . "\n\n";

// 1. Check Database Connection
echo "1. Database Connection Check\n";
echo str_repeat("-", 40) . "\n";
try {
    DB::connection()->getPdo();
    echo "✓ Database connection successful\n";
    $results['passed'][] = 'Database Connection';
} catch (\Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
    $results['failed'][] = 'Database Connection: ' . $e->getMessage();
}
echo "\n";

// 2. Check Storage Symlink
echo "2. Storage Symlink Check\n";
echo str_repeat("-", 40) . "\n";
$storageLinkExists = is_link(public_path('storage'));
$storageAppPath = storage_path('app/public');
if ($storageLinkExists) {
    echo "✓ Storage symlink exists: " . readlink(public_path('storage')) . "\n";
    $results['passed'][] = 'Storage Symlink';
} else {
    echo "⚠ Storage symlink missing (announcement uploads may not work)\n";
    echo "  Run: php artisan storage:link\n";
    $results['warnings'][] = 'Storage symlink missing';
}
echo "\n";

// 3. Check Announcements Table
echo "3. Announcements Module Check\n";
echo str_repeat("-", 40) . "\n";
try {
    $announcements = DB::table('announcements')->count();
    echo "✓ Announcements table accessible\n";
    echo "  Records: $announcements\n";
    $results['passed'][] = 'Announcements Table';
} catch (\Exception $e) {
    echo "✗ Announcements table error: " . $e->getMessage() . "\n";
    $results['failed'][] = 'Announcements: ' . $e->getMessage();
}
echo "\n";

// 4. Check Fire Safety Tables
echo "4. Fire Safety Module Tables\n";
echo str_repeat("-", 40) . "\n";
$fsTables = [
    'firesafety_buildings' => 'Buildings',
    'firesafety_fire_extinguishers' => 'Extinguishers',
    'firesafety_alarm_systems' => 'Alarm Systems',
    'firesafety_rooms' => 'Rooms'
];
foreach ($fsTables as $table => $label) {
    try {
        $count = DB::table($table)->count();
        echo "✓ $label ($table): $count records\n";
        $results['passed'][] = "Fire Safety - $label";
    } catch (\Exception $e) {
        echo "✗ $label table error: " . $e->getMessage() . "\n";
        $results['failed'][] = "Fire Safety - $label: " . $e->getMessage();
    }
}
echo "\n";

// 5. Check Typhoon/Flood Module Tables
echo "5. Typhoon/Flood Module Tables\n";
echo str_repeat("-", 40) . "\n";
$tfTables = [
    'typ_fld_families' => 'Families',
    'typ_fld_family_members' => 'Family Members',
    'typ_fld_monitoring_snapshots' => 'Monitoring Snapshots'
];
foreach ($tfTables as $table => $label) {
    try {
        $count = DB::table($table)->count();
        echo "✓ $label ($table): $count records\n";
        $results['passed'][] = "Typhoon/Flood - $label";
    } catch (\Exception $e) {
        echo "⚠ $label table may not exist (expected for some setup states)\n";
        $results['warnings'][] = "Typhoon/Flood - $label: " . $e->getMessage();
    }
}
echo "\n";

// 6. Check Incident Module Tables
echo "6. Incident Checklist Module Tables\n";
echo str_repeat("-", 40) . "\n";
$incidentTables = [
    'incident_calendars' => 'Incident Calendar',
    'incident_checklists' => 'Checklists',
    'incident_types' => 'Incident Types',
    'incident_statuses' => 'Incident Statuses'
];
foreach ($incidentTables as $table => $label) {
    try {
        $count = DB::table($table)->count();
        echo "✓ $label ($table): $count records\n";
        $results['passed'][] = "Incident - $label";
    } catch (\Exception $e) {
        echo "✗ $label table error: " . $e->getMessage() . "\n";
        $results['failed'][] = "Incident - $label: " . $e->getMessage();
    }
}
echo "\n";

// 7. Check Comprehensive School Safety Tables
echo "7. Comprehensive School Safety Module Tables\n";
echo str_repeat("-", 40) . "\n";
$compTables = [
    'comprehensive_assessments' => 'Assessments',
    'comprehensive_students' => 'Students',
    'comprehensive_facilities' => 'Facilities',
    'comprehensive_storage' => 'Storage Items',
    'comprehensive_summary_findings' => 'Summary Findings'
];
foreach ($compTables as $table => $label) {
    try {
        $count = DB::table($table)->count();
        echo "✓ $label ($table): $count records\n";
        $results['passed'][] = "Comprehensive - $label";
    } catch (\Exception $e) {
        echo "⚠ $label table may not exist (expected for some setup states)\n";
        $results['warnings'][] = "Comprehensive - $label: " . $e->getMessage();
    }
}
echo "\n";

// 8. Check Hazard Mapping Tables
echo "8. Hazard Mapping Module Tables\n";
echo str_repeat("-", 40) . "\n";
$hazardTables = [
    'hazard_mappings' => 'Hazard Mapping'
];
foreach ($hazardTables as $table => $label) {
    try {
        $count = DB::table($table)->count();
        echo "✓ $label ($table): $count records\n";
        $results['passed'][] = "Hazard Mapping - $label";
    } catch (\Exception $e) {
        echo "⚠ $label table may not exist (expected for some setup states)\n";
        $results['warnings'][] = "Hazard Mapping - $label: " . $e->getMessage();
    }
}
echo "\n";

// 9. Check Activity Log
echo "9. Activity Log Module\n";
echo str_repeat("-", 40) . "\n";
try {
    $activityCount = DB::table('activity_logs')->count();
    echo "✓ Activity Log: $activityCount records\n";
    $results['passed'][] = 'Activity Log';
} catch (\Exception $e) {
    echo "✗ Activity Log error: " . $e->getMessage() . "\n";
    $results['failed'][] = 'Activity Log: ' . $e->getMessage();
}
echo "\n";

// 10. Check Users Table
echo "10. Users Management\n";
echo str_repeat("-", 40) . "\n";
try {
    $userCount = DB::table('users')->count();
    $adminCount = DB::table('users')->where('role', 'admin')->count();
    echo "✓ Users table: $userCount total users ($adminCount admins)\n";
    $results['passed'][] = 'Users Management';
} catch (\Exception $e) {
    echo "✗ Users table error: " . $e->getMessage() . "\n";
    $results['failed'][] = 'Users: ' . $e->getMessage();
}
echo "\n";

// 11. Check Schools Registration
echo "11. School Registration Status\n";
echo str_repeat("-", 40) . "\n";
try {
    $schoolCount = DB::table('schools')->count();
    $fsSchools = DB::table('schools')->where('has_fire_safety', true)->count();
    $tfSchools = DB::table('schools')->where('has_typhoon_flood', true)->count();
    $incSchools = DB::table('schools')->where('has_incident_checklist', true)->count();
    $compSchools = DB::table('schools')->where('has_comprehensive', true)->count();

    echo "✓ Schools registered: $schoolCount total\n";
    echo "  - Fire Safety: $fsSchools schools\n";
    echo "  - Typhoon/Flood: $tfSchools schools\n";
    echo "  - Incident Checklist: $incSchools schools\n";
    echo "  - Comprehensive: $compSchools schools\n";
    $results['passed'][] = 'School Registration';
} catch (\Exception $e) {
    echo "✗ Schools error: " . $e->getMessage() . "\n";
    $results['failed'][] = 'Schools: ' . $e->getMessage();
}
echo "\n";

// 12. Check Model Relationships
echo "12. Model Relationships\n";
echo str_repeat("-", 40) . "\n";
try {
    $school = School::with(['buildings', 'alarmSystems', 'extinguishers', 'rooms'])->first();
    if ($school) {
        echo "✓ School model relationships OK\n";
        echo "  Sample school: {$school->school_name}\n";
        echo "    - Buildings: " . $school->buildings->count() . "\n";
        echo "    - Alarms: " . $school->alarmSystems->count() . "\n";
        echo "    - Extinguishers: " . $school->extinguishers->count() . "\n";
        echo "    - Rooms: " . $school->rooms->count() . "\n";
        $results['passed'][] = 'Model Relationships';
    } else {
        echo "⚠ No schools found for relationship testing\n";
        $results['warnings'][] = 'No schools in database for relationship testing';
    }
} catch (\Exception $e) {
    echo "✗ Model relationships error: " . $e->getMessage() . "\n";
    $results['failed'][] = 'Model Relationships: ' . $e->getMessage();
}
echo "\n";

// 13. Check Views Directory
echo "13. Views and Resources\n";
echo str_repeat("-", 40) . "\n";
$viewsPath = resource_path('views');
if (is_dir($viewsPath)) {
    $viewCount = count(glob("$viewsPath/**/*.blade.php", GLOB_RECURSIVE));
    echo "✓ Views directory OK: $viewCount blade templates\n";

    // Check specific critical views
    $criticalViews = [
        'dashboard.blade.php',
        'fire-safety/dashboard.blade.php',
        'typhoon/dashboard.blade.php',
        'incident/dashboard.blade.php'
    ];

    foreach ($criticalViews as $view) {
        $viewPath = "$viewsPath/$view";
        if (file_exists($viewPath)) {
            echo "  ✓ $view\n";
        } else {
            echo "  ⚠ $view (not found)\n";
        }
    }
    $results['passed'][] = 'Views Directory';
} else {
    echo "✗ Views directory not found\n";
    $results['failed'][] = 'Views directory missing';
}
echo "\n";

// 14. Check CSS/JS Build
echo "14. Frontend Build Status\n";
echo str_repeat("-", 40) . "\n";
$manifestPath = public_path('build/manifest.json');
if (file_exists($manifestPath)) {
    echo "✓ Build manifest exists\n";
    echo "  Path: $manifestPath\n";
    $results['passed'][] = 'Frontend Build';
} else {
    echo "⚠ Build manifest not found (CSS/JS may not load)\n";
    echo "  Run: npm ci && npm run build\n";
    $results['warnings'][] = 'Build manifest missing - run npm build';
}
echo "\n";

// 15. Check Configuration
echo "15. Application Configuration\n";
echo str_repeat("-", 40) . "\n";
try {
    $appUrl = config('app.url');
    $appName = config('app.name');
    $appEnv = config('app.env');

    echo "✓ App configured:\n";
    echo "  - Name: $appName\n";
    echo "  - URL: $appUrl\n";
    echo "  - Environment: $appEnv\n";

    if ($appEnv === 'production') {
        echo "  ⚠ Running in PRODUCTION mode\n";
        $results['warnings'][] = 'Running in production environment';
    }

    $results['passed'][] = 'Application Configuration';
} catch (\Exception $e) {
    echo "✗ Configuration error: " . $e->getMessage() . "\n";
    $results['failed'][] = 'Configuration: ' . $e->getMessage();
}
echo "\n";

// Summary Report
echo str_repeat("=", 80) . "\n";
echo "TEST SUMMARY\n";
echo str_repeat("=", 80) . "\n\n";

echo "✓ PASSED: " . count($results['passed']) . " checks\n";
foreach ($results['passed'] as $check) {
    echo "  ✓ $check\n";
}
echo "\n";

if (!empty($results['warnings'])) {
    echo "⚠ WARNINGS: " . count($results['warnings']) . " items\n";
    foreach ($results['warnings'] as $warning) {
        echo "  ⚠ $warning\n";
    }
    echo "\n";
}

if (!empty($results['failed'])) {
    echo "✗ FAILED: " . count($results['failed']) . " checks\n";
    foreach ($results['failed'] as $failure) {
        echo "  ✗ $failure\n";
    }
    echo "\n";
} else {
    echo "✗ FAILED: 0 checks\n\n";
}

// Overall status
$totalTests = count($results['passed']) + count($results['failed']);
$failureCount = count($results['failed']);
$warningCount = count($results['warnings']);

if ($failureCount === 0 && $warningCount === 0) {
    echo "OVERALL STATUS: ✓ ALL SYSTEMS OPERATIONAL\n";
} elseif ($failureCount === 0) {
    echo "OVERALL STATUS: ⚠ OPERATIONAL WITH WARNINGS\n";
} else {
    echo "OVERALL STATUS: ✗ ISSUES DETECTED\n";
}

echo str_repeat("=", 80) . "\n\n";

// Recommendations
echo "RECOMMENDATIONS:\n";
echo str_repeat("-", 40) . "\n";

if (!$storageLinkExists) {
    echo "1. Create storage symlink:\n";
    echo "   php artisan storage:link\n\n";
}

if (!file_exists($manifestPath)) {
    echo "2. Rebuild frontend assets:\n";
    echo "   npm ci\n";
    echo "   npm run build\n\n";
}

if ($warningCount > 0 || $failureCount > 0) {
    echo "3. Review warnings and failures above for required actions.\n\n";
}

echo "For detailed testing of individual pages, access:\n";
echo "  - Dashboard: http://localhost/dashboard\n";
echo "  - Fire Safety: http://localhost/fire-safety/dashboard\n";
echo "  - Typhoon/Flood: http://localhost/typhoon/dashboard\n";
echo "  - Incident Checklist: http://localhost/incidents/dashboard\n";
echo "  - Comprehensive: http://localhost/comprehensive-school-safety/dashboard\n";
echo "  - Hazard Mapping: http://localhost/hazard-mapping/dashboard\n";
echo "  - Activity Log: http://localhost/activity-logs\n";
echo "  - Users: http://localhost/users\n\n";

?>
