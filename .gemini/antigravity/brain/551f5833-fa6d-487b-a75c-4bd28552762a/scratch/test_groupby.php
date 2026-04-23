<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\SystemConfiguration;

$alarmStatusesByType = SystemConfiguration::where('config_type', 'alarm_status')->where('is_active', true)->get()->groupBy('parent_id');

echo "Keys in groupBy('parent_id'):\n";
foreach ($alarmStatusesByType as $key => $val) {
    var_dump($key);
}
