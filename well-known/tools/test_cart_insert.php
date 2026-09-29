<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

try {
    $user = DB::table('users')->first();
    if (! $user) {
        echo "no_user: create a user first" . PHP_EOL;
        exit(2);
    }

    $product = DB::table('products')->first();
    if (! $product) {
        echo "no_product: no products in database" . PHP_EOL;
        exit(3);
    }

    $now = Carbon::now()->toDateTimeString();

    $id = DB::table('cart')->insertGetId([
        'user_id' => $user->id,
        'product_id' => $product->id,
        'quantity' => 1,
        'price' => $product->final_price ?? $product->total_price ?? 0,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    echo "ok: inserted cart id={$id}, product_id={$product->id}\n";
    exit(0);
} catch (\Exception $e) {
    echo "error:" . $e->getMessage() . PHP_EOL;
    exit(1);
}
