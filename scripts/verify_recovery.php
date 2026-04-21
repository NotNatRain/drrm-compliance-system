<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$ids = ['107137', '107130', '107129'];

foreach ($ids as $sid) {
    $s = App\Models\School::where('school_id', $sid)->first();
    if (!$s) {
        echo "MISSING|{$sid}\n";
        continue;
    }

    $b = App\Models\FireSafetyBuilding::where('unified_school_id', $s->id)->count();
    $a = App\Models\FireSafetyAlarmSystem::where('unified_school_id', $s->id)->count();
    $e = App\Models\FireSafetyExtinguisher::where('unified_school_id', $s->id)->count();
    $r = App\Models\FireSafetyRoom::where('unified_school_id', $s->id)->count();

    echo $s->school_name . '|' . $s->school_id . "|B:{$b}|A:{$a}|E:{$e}|R:{$r}\n";

    $alarmRows = App\Models\FireSafetyAlarmSystem::where('unified_school_id', $s->id)
        ->orderBy('code')
        ->get(['code', 'status', 'notes']);

    foreach ($alarmRows as $ar) {
        $n = trim((string) $ar->notes);
        echo "  ALARM|{$ar->code}|{$ar->status}|" . ($n !== '' ? $n : '-') . "\n";
    }

    $extRows = App\Models\FireSafetyExtinguisher::where('unified_school_id', $s->id)
        ->orderBy('code')
        ->get(['code', 'status', 'remarks']);

    foreach ($extRows as $ex) {
        $rm = trim((string) $ex->remarks);
        echo "  EXT|{$ex->code}|{$ex->status}|" . ($rm !== '' ? $rm : '-') . "\n";
    }
}
