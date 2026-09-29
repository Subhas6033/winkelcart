<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$response = $kernel->handle($request = Illuminate\Http\Request::capture());

use App\Models\Product;

$product = Product::where('name', 'pppppppp')->first();

if ($product) {
    echo "Product ID: " . $product->id . "\n";
    echo "Created By: " . $product->created_by . "\n";
    echo "Status: " . $product->status . "\n";
    echo "Deleted: " . $product->deleted . "\n";
    echo "Category: " . ($product->category ? $product->category->name : 'NULL') . "\n";
} else {
    echo "Product not found\n";
}
