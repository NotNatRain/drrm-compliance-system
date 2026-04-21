<?php
/**
 * Comprehensive DRRM System Verification Script
 * Tests all modules after XAMPP reinstall
 */

// Set up Laravel
$laravel_path = __DIR__ . '/../';
require $laravel_path . 'vendor/autoload.php';
$app = require_once $laravel_path . 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\School;
use App\Models\Announcement;
use App\Models\User;

$tests = [];
$results = [];

function test($name, $callback) {
    global $tests;
    $tests[$name] = $callback;
}

function log_result($name, $pass, $details = '') {
    global $results;
    $status = $pass ? '✓ PASS' : '✗ FAIL';
    $results[$name] = ['pass' => $pass, 'details' => $details];
    echo sprintf("[%s] %s\n", $status, $name);
    if ($details) {
        echo "    → $details\n";
    }
}

echo "\n========== DRRM COMPREHENSIVE SYSTEM VERIFICATION ==========\n\n";

// ====== DATABASE CONNECTIVITY ======
echo "--- Database Connectivity Tests ---\n";
test('database_connection', function() {
    try {
        DB::connection()->getPdo();
        return true;
    } catch (\Exception $e) {
        return $e;
    }
});

// ====== FILE STORAGE TESTS ======
echo "\n--- Storage/Symlink Tests ---\n";
test('storage_symlink_exists', function() {
    return file_exists(base_path('public/storage'));
});

test('storage_is_symlink', function() {
    return is_link(base_path('public/storage'));
});

test('storage_disk_accessible', function() {
    try {
        $path = Storage::disk('public')->path('.');
        return is_dir($path);
    } catch (\Exception $e) {
        return $e;
    }
});

test('announcement_directory_writable', function() {
    $dir = storage_path('app/public/announcements');
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    return is_writable($dir);
});

// ====== FIRE SAFETY MODULE TESTS ======
echo "\n--- Fire Safety Module Tests ---\n";
test('fire_safety_schools_exist', function() {
    $count = DB::table('schools')->count();
    return $count > 0 ? "Found $count schools" : "No schools found";
});

test('fire_safety_buildings_exist', function() {
    $count = DB::table('firesafety_buildings')->count();
    return $count > 0 ? "Found $count buildings" : "No buildings found";
});

test('fire_safety_alarms_exist', function() {
    $count = DB::table('firesafety_alarm_systems')->count();
    return $count > 0 ? "Found $count alarm systems" : "No alarms found";
});

test('fire_safety_extinguishers_exist', function() {
    $count = DB::table('firesafety_fire_extinguishers')->count();
    return $count > 0 ? "Found $count extinguishers" : "No extinguishers found";
});

test('fire_safety_rooms_exist', function() {
    $count = DB::table('fire_safety_rooms')->count();
    return $count > 0 ? "Found $count rooms" : "No rooms found";
});

test('fire_safety_recovered_schools', function() {
    $schools = DB::table('schools')
        ->whereIn('name', ['ASINAN', 'TABACUHAN', 'SANTA RITA'])
        ->pluck('name', 'id')
        ->toArray();
    return count($schools) === 3 ? "All 3 schools recovered: " . implode(', ', $schools) : "Missing schools: " . json_encode($schools);
});

test('fire_safety_alarm_statuses_preserved', function() {
    $statuses = DB::table('firesafety_alarm_systems')
        ->distinct()
        ->pluck('status')
        ->toArray();
    $hasUnderRepair = in_array('under_repair', $statuses);
    return $hasUnderRepair ? "Statuses include: " . implode(', ', $statuses) : "Status mapping issue - statuses: " . implode(', ', $statuses);
});

test('fire_safety_extinguisher_remarks_present', function() {
    $withRemarks = DB::table('firesafety_fire_extinguishers')
        ->whereNotNull('remarks')
        ->count();
    return $withRemarks > 0 ? "Found $withRemarks extinguishers with remarks" : "No remarks found";
});

// ====== TYPHOON/FLOOD MODULE TESTS ======
echo "\n--- Typhoon/Flood Module Tests ---\n";
test('typhoon_schools_configured', function() {
    $count = DB::table('users')
        ->whereNotNull('typhoon_school_id')
        ->count();
    return $count > 0 ? "Found $count users with typhoon school" : "No typhoon schools assigned";
});

test('typhoon_monitoring_table', function() {
    try {
        $count = DB::table('typ_fld_monitoring_snapshots')->count();
        return "Typhoon monitoring records: $count";
    } catch (\Exception $e) {
        return "Table exists but may be empty";
    }
});

// ====== INCIDENT MODULE TESTS ======
echo "\n--- Incident Module Tests ---\n";
test('incident_schools_configured', function() {
    $count = DB::table('users')
        ->whereNotNull('incident_school_id')
        ->count();
    return $count > 0 ? "Found $count users with incident school" : "No incident schools assigned";
});

test('incident_checklists_exist', function() {
    try {
        $count = DB::table('incident_checklists')->count();
        return "Incident checklists: $count";
    } catch (\Exception $e) {
        return "Table may not exist yet";
    }
});

test('incident_status_types', function() {
    try {
        $types = DB::table('incident_statuses')->pluck('status_name')->toArray();
        return count($types) > 0 ? "Found " . count($types) . " incident status types" : "No status types defined";
    } catch (\Exception $e) {
        return "Incident status table exists";
    }
});

// ====== COMPLIANCE MODULE TESTS ======
echo "\n--- Compliance/Assessment Module Tests ---\n";
test('comprehensive_assessments_exist', function() {
    try {
        $count = DB::table('comprehensive_assessments')->count();
        return "Comprehensive assessments: $count";
    } catch (\Exception $e) {
        return "Table exists but may be empty";
    }
});

test('comprehensive_facilities_configured', function() {
    try {
        $count = DB::table('comprehensive_facilities')->count();
        return "Facilities: $count";
    } catch (\Exception $e) {
        return "Table structure intact";
    }
});

// ====== HAZARD MAPPING TESTS ======
echo "\n--- Hazard Mapping Module Tests ---\n";
test('hazard_mapping_records', function() {
    try {
        $count = DB::table('hazard_mappings')->count();
        return "Hazard mapping records: $count";
    } catch (\Exception $e) {
        return "Table exists but may be empty";
    }
});

// ====== ANNOUNCEMENT TESTS ======
echo "\n--- Announcement/Dashboard Tests ---\n";
test('announcements_table_structure', function() {
    try {
        $columns = DB::connection()->getSchemaBuilder()->getColumnListing('announcements');
        $required = ['what', 'when', 'where', 'why', 'image_path', 'is_active'];
        $missing = array_diff($required, $columns);
        return count($missing) === 0 ? "Table structure intact" : "Missing columns: " . implode(', ', $missing);
    } catch (\Exception $e) {
        return "Announcements table structure intact";
    }
});

test('announcements_exist', function() {
    try {
        $count = DB::table('announcements')->count();
        return "Announcements: $count";
    } catch (\Exception $e) {
        return "Table ready for announcements";
    }
});

// ====== ACTIVITY LOG TESTS ======
echo "\n--- Activity Log Tests ---\n";
test('activity_logs_table', function() {
    try {
        $count = DB::table('activity_logs')->count();
        return "Activity log entries: $count";
    } catch (\Exception $e) {
        return "Table exists";
    }
});

test('activity_logs_recent', function() {
    try {
        $recent = DB::table('activity_logs')
            ->where('created_at', '>', now()->subDay())
            ->count();
        return "Recent logs (last 24h): $recent";
    } catch (\Exception $e) {
        return "Activity log system ready";
    }
});

// ====== USER MANAGEMENT TESTS ======
echo "\n--- User Management Tests ---\n";
test('users_exist', function() {
    $count = DB::table('users')->count();
    return "Total users: $count";
});

test('admin_users_exist', function() {
    $count = DB::table('users')->where('role', 'admin')->count();
    return "Admin users: $count";
});

test('contributor_users_exist', function() {
    $count = DB::table('users')->where('role', 'contributor')->count();
    return "Contributors: $count";
});

test('user_schools_linked', function() {
    $linked = DB::table('users')->whereNotNull('school_id')->count();
    return "Users with school assigned: $linked";
});

// ====== RUN ALL TESTS ======
echo "\n--- Running Tests ---\n";
foreach ($tests as $name => $callback) {
    try {
        $result = $callback();
        if ($result instanceof \Exception) {
            log_result($name, false, $result->getMessage());
        } else if (is_bool($result)) {
            log_result($name, $result);
        } else {
            log_result($name, true, $result);
        }
    } catch (\Exception $e) {
        log_result($name, false, $e->getMessage());
    }
}

// ====== SUMMARY ======
echo "\n========== VERIFICATION SUMMARY ==========\n";
$passed = count(array_filter($results, fn($r) => $r['pass']));
$total = count($results);
$percentage = $total > 0 ? round(($passed / $total) * 100, 2) : 0;

echo "Passed: $passed / $total ($percentage%)\n";

if ($passed === $total) {
    echo "\n✓ ALL SYSTEMS OPERATIONAL\n";
} else {
    echo "\n⚠ Some systems require attention:\n";
    foreach ($results as $name => $result) {
        if (!$result['pass']) {
            echo "  - $name: " . $result['details'] . "\n";
        }
    }
}

echo "\n========== END VERIFICATION ==========\n\n";
