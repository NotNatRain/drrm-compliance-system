<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\School;
use App\Models\SchoolSpecificsInformation;
use Illuminate\Support\Facades\DB;

$totalSchools = School::count();
echo "Total Schools: $totalSchools\n";

$modules = ['fire_safety', 'typhoon_flood', 'comprehensive_school_safety'];
foreach ($modules as $module) {
    $count = School::whereHas('specifics', function($q) use ($module) {
        $q->where('module', $module);
    })->count();
    echo "Registered in $module: $count\n";
}

// Logic to register all schools to all modules if missing
$schools = School::all();
foreach ($schools as $school) {
    foreach ($modules as $module) {
        $exists = SchoolSpecificsInformation::where('school_id', $school->id)
            ->where('module', $module)
            ->exists();
            
        if (!$exists) {
            echo "Registering {$school->school_name} to $module...\n";
            
            $key = '';
            if ($module == 'fire_safety') $key = 'original_fire_safety_id';
            elseif ($module == 'typhoon_flood') {
                $key = 'original_evacuation_center_id';
                // Set default capacity if missing
                if (!$school->evacuation_capacity) {
                    $school->evacuation_capacity = 500;
                    $school->save();
                }
            }
            elseif ($module == 'comprehensive_school_safety') $key = 'original_assessment_id';
            
            SchoolSpecificsInformation::create([
                'school_id' => $school->id,
                'module' => $module,
                'key' => $key,
                'value' => 'AUTO_' . $school->id
            ]);
        }
    }
}

echo "Registration complete.\n";
