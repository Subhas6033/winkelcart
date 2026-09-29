<?php
// Diagnostic tool for checking product visibility on the frontend.
// Run: php tools/debug_products.php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$count = \App\Models\Product::where('deleted', 0)->where('status', 0)->fromVerifiedSellers()->count();
echo "Products passing frontend filter: {$count}\n";
