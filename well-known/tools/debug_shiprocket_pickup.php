<?php
/**
 * Temporary diagnostic: raw Shiprocket pickup API response + seller KYC data
 * Usage: php artisan tinker tools/debug_shiprocket_pickup.php
 * OR:    php -r "define('LARAVEL_START', microtime(true)); require __DIR__.'/../vendor/autoload.php'; $app = require_once __DIR__.'/../bootstrap/app.php'; $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class); $kernel->bootstrap(); require __DIR__.'/debug_shiprocket_pickup.php';"
 * Simplest: Run via artisan eval below.
 */

use App\Models\SellerKycVerification;
use App\Services\ShiprocketService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

// Bootstrap Laravel
define('LARAVEL_START', microtime(true));
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "\n=== Shiprocket Pickup Debug ===\n\n";

// 1. Raw API response
$service = new ShiprocketService();
$token = $service->getToken(true);  // force-refresh
echo "Token: " . ($token ? substr($token, 0, 30) . '...' : 'FAILED') . "\n\n";

if ($token) {
    $response = Http::withToken($token)
        ->timeout(15)
        ->get('https://apiv2.shiprocket.in/v1/external/settings/company/pickup');

    echo "HTTP Status: " . $response->status() . "\n";
    echo "Raw Response:\n";
    echo json_encode($response->json(), JSON_PRETTY_PRINT) . "\n\n";
}

// 2. Seller #631 KYC data
$kyc = SellerKycVerification::with('seller.user_info')->where('user_id', 631)->first();
if ($kyc) {
    echo "=== Seller #631 KYC ===\n";
    echo "pickup_location    : " . $kyc->shiprocket_pickup_location . "\n";
    echo "pickup_sync_status : " . ($kyc->pickup_sync_status ?? 'null') . "\n";
    echo "pickup_address     : " . ($kyc->pickup_address ?? 'NULL') . "\n";
    echo "pickup_city        : " . ($kyc->pickup_city ?? 'NULL') . "\n";
    echo "pickup_state       : " . ($kyc->pickup_state ?? 'NULL') . "\n";
    echo "pickup_pincode     : " . ($kyc->pickup_pincode ?? 'NULL') . "\n";
    echo "pickup_phone       : " . ($kyc->pickup_phone ?? 'NULL') . "\n";
    echo "pickup_email       : " . ($kyc->pickup_email ?? 'NULL') . "\n";
    echo "\n=== Seller user_info fallback ===\n";
    $info = $kyc->seller?->user_info;
    echo "address_line_1 : " . ($info?->address_line_1 ?? 'NULL') . "\n";
    echo "city           : " . ($info?->city ?? 'NULL') . "\n";
    echo "state          : " . ($info?->state ?? 'NULL') . "\n";
    echo "pincode        : " . ($info?->pincode ?? 'NULL') . "\n";
    echo "phone          : " . ($info?->phone ?? 'NULL') . "\n";
    echo "seller email   : " . ($kyc->seller?->email ?? 'NULL') . "\n";
    echo "legal_name     : " . ($kyc->legal_name ?? 'NULL') . "\n";
} else {
    echo "Seller #631 KYC not found\n";
}
