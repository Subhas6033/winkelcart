<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

DB::table('orders')
    ->where('order_number', 'AM5YY7M5WK')
    ->update([
        'pickup_requested'    => 0,
        'pickup_requested_at' => null,
    ]);

$row = DB::table('orders')->where('order_number', 'AM5YY7M5WK')->first();
echo "pickup_requested: " . $row->pickup_requested . PHP_EOL;
echo "awb_code: " . ($row->awb_code ?? 'NULL') . PHP_EOL;
echo "shiprocket_shipment_id: " . ($row->shiprocket_shipment_id ?? 'NULL') . PHP_EOL;
echo "shiprocket_order_id: " . ($row->shiprocket_order_id ?? 'NULL') . PHP_EOL;
echo "Done." . PHP_EOL;
