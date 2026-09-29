<?php
// Simple script to inspect products and categories in the app database.
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $prodCount = DB::select("SELECT COUNT(*) AS c FROM products");
    $prodCount = $prodCount[0]->c ?? 0;
    echo "products_count:" . $prodCount . PHP_EOL;

    $cols = DB::select("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'products'");
    echo "products_columns:" . PHP_EOL;
    foreach ($cols as $c) {
        echo " - " . $c->COLUMN_NAME . PHP_EOL;
    }

    $catCount = DB::select("SELECT COUNT(*) AS c FROM categories WHERE status = 0");
    $catCount = $catCount[0]->c ?? 0;
    echo "categories_status_0_count:" . $catCount . PHP_EOL;
} catch (\Exception $e) {
    echo "error:" . $e->getMessage() . PHP_EOL;
    exit(1);
}

exit(0);
