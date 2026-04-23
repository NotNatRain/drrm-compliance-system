<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "Table 'typ_fld_needs' exists: " . (Schema::hasTable('typ_fld_needs') ? 'Yes' : 'No') . "\n";
if (Schema::hasTable('typ_fld_needs')) {
    echo "Column 'need_name' exists: " . (Schema::hasColumn('typ_fld_needs', 'need_name') ? 'Yes' : 'No') . "\n";
    $columns = DB::select('DESCRIBE typ_fld_needs');
    echo "Actual columns:\n";
    print_r($columns);
}
