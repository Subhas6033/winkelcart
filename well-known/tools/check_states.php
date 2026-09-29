<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $cols = DB::select("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'states'");
    echo "states_columns:" . PHP_EOL;
    foreach ($cols as $c) {
        echo " - " . $c->COLUMN_NAME . PHP_EOL;
    }
    $count = DB::select("SELECT COUNT(*) AS c FROM states");
    echo "states_count: " . ($count[0]->c ?? 0) . PHP_EOL;
} catch (\Exception $e) {
    echo "error:" . $e->getMessage() . PHP_EOL;
    exit(1);
}

exit(0);
