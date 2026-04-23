<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\SystemConfiguration;

$results = SystemConfiguration::where('name', 'like', '%Purchase%')->get();
if ($results->isEmpty()) {
    echo "No matching items found for 'Purchase'.\n";
} else {
    foreach ($results as $item) {
        echo "Type: {$item->config_type}, Name: {$item->name}, ID: {$item->id}, Parent: " . ($item->parent_id ?: 'None') . "\n";
    }
}
