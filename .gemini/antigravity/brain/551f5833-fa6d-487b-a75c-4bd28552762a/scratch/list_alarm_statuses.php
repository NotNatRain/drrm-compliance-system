<?php
require __DIR__ . '/../../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\SystemConfiguration;

$statuses = SystemConfiguration::where('config_type', 'alarm_status')->get();

echo "Alarm Statuses in DB:\n";
foreach ($statuses as $s) {
    echo "- Name: {$s->name}, Parent ID: " . ($s->parent_id ?: 'null') . ", Active: " . ($s->is_active ? 'Yes' : 'No') . "\n";
}

$extinguisher_statuses = SystemConfiguration::where('config_type', 'extinguisher_status')->get();
echo "\nExtinguisher Statuses in DB:\n";
foreach ($extinguisher_statuses as $s) {
    echo "- Name: {$s->name}, Parent ID: " . ($s->parent_id ?: 'null') . ", Active: " . ($s->is_active ? 'Yes' : 'No') . "\n";
}
