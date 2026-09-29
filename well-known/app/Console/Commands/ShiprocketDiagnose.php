<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\ShiprocketService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * Artisan command to diagnose Shiprocket integration issues.
 *
 * Usage:
 *   php artisan shiprocket:diagnose              # run all checks
 *   php artisan shiprocket:diagnose --order=100  # test with a real order ID
 */
class ShiprocketDiagnose extends Command
{
    protected $signature   = 'shiprocket:diagnose {--order= : Order ID to test full shipment flow}';
    protected $description = 'Diagnose Shiprocket API connectivity, token, and order-creation payload';

    public function handle(): int
    {
        $this->info('======= Shiprocket Integration Diagnostics =======');

        // 1. Config check
        $this->checkConfig();

        // 2. Token
        $token = $this->checkToken();
        if (!$token) {
            return self::FAILURE;
        }

        // 3. Pickup locations
        $this->checkPickupLocations($token);

        // 4. Optional: test with a real order
        if ($orderId = $this->option('order')) {
            $this->testOrder((int) $orderId, $token);
        }

        $this->info('');
        $this->info('Diagnostics complete. Check laravel.log for full detail on any errors.');
        return self::SUCCESS;
    }

    // ─── Steps ──────────────────────────────────────────────────────────────

    private function checkConfig(): void
    {
        $this->line('');
        $this->comment('[1] Config values');

        $email          = config('services.shiprocket.email');
        $password       = config('services.shiprocket.password');
        $pickupLocation = config('services.shiprocket.pickup_location');
        $channelId      = config('services.shiprocket.channel_id');

        $this->line('  Email            : ' . ($email    ? substr($email, 0, 4) . '***' : '<MISSING>'));
        $this->line('  Password         : ' . ($password ? '***SET***'              : '<MISSING>'));
        $this->line('  Pickup location  : ' . ($pickupLocation ?: '<MISSING — set SHIPROCKET_PICKUP_LOCATION in .env>'));
        $this->line('  Channel ID       : ' . ($channelId      ?: '(not set — will be omitted from payload)'));

        if (!$email || !$password) {
            $this->error('  → SHIPROCKET_EMAIL and/or SHIPROCKET_PASSWORD not set in .env');
        }
        if (!$pickupLocation) {
            $this->warn('  → SHIPROCKET_PICKUP_LOCATION is empty. Default "Primary" will be used.');
        }
    }

    private function checkToken(): ?string
    {
        $this->line('');
        $this->comment('[2] Authentication token');

        // Force a fresh token to verify credentials are correct right now
        cache()->forget('shiprocket_token');

        $service = new ShiprocketService();
        $token = $service->getToken(forceRefresh: true);

        if ($token) {
            $this->info('  ✔ Token obtained successfully: ' . substr($token, 0, 20) . '...');
        } else {
            $this->error('  ✗ Could not obtain token. Check SHIPROCKET_EMAIL and SHIPROCKET_PASSWORD in .env.');
        }

        return $token;
    }

    private function checkPickupLocations(string $token): void
    {
        $this->line('');
        $this->comment('[3] Registered pickup locations (from Shiprocket panel)');

        $baseUrl = config('services.shiprocket.base_url');

        try {
            $response = Http::withToken($token)
                ->timeout(15)
                ->get("{$baseUrl}/v1/external/settings/company/pickup");

            if (!$response->successful()) {
                $this->error('  ✗ Could not fetch pickup locations: HTTP ' . $response->status());
                $this->line('    Response: ' . json_encode($response->json()));
                return;
            }

            $locations = $response->json('data.shipping_address') ?? [];

            if (empty($locations)) {
                $this->warn('  ⚠ No pickup locations found. Go to Settings > Manage Pickups in Shiprocket panel and add one.');
                return;
            }

            $configuredName = config('services.shiprocket.pickup_location', 'Primary');
            $found          = false;

            foreach ($locations as $loc) {
                $name      = $loc['pickup_location'] ?? $loc['name'] ?? 'N/A';
                $pincode   = $loc['pin_code'] ?? 'N/A';
                $isMatch   = ($name === $configuredName);
                $marker    = $isMatch ? ' ← MATCHED' : '';
                $this->line("  • {$name} (pincode: {$pincode}){$marker}");
                if ($isMatch) {
                    $found = true;
                }
            }

            if (!$found) {
                $this->error("  ✗ Configured pickup location \"{$configuredName}\" not found in the list above.");
                $this->error("    Update SHIPROCKET_PICKUP_LOCATION in .env to exactly match one of the names above.");
            } else {
                $this->info("  ✔ Configured pickup location \"{$configuredName}\" exists.");
            }
        } catch (\Exception $e) {
            $this->error('  ✗ Exception: ' . $e->getMessage());
        }
    }

    private function testOrder(int $orderId, string $token): void
    {
        $this->line('');
        $this->comment("[4] Test order creation — Order ID #{$orderId}");

        $order = Order::with(['items.product.seller.kycVerification', 'buyer.user_info'])
            ->find($orderId);

        if (!$order) {
            $this->error("  ✗ Order #{$orderId} not found.");
            return;
        }

        $this->line('  Order #   : ' . $order->order_number);
        $this->line('  Payment   : ' . $order->payment_method);
        $this->line('  Total     : ₹' . $order->total_amount);

        $buyer   = $order->buyer;
        $info    = $buyer?->user_info;

        // Phone check
        $rawPhone = $info?->phone ?? ($buyer?->mobile ?? '');
        $phone    = preg_replace('/\D/', '', $rawPhone);
        if (strlen($phone) === 12 && str_starts_with($phone, '91')) {
            $phone = substr($phone, 2);
        }
        $phoneOk = strlen($phone) === 10;
        $this->line('  Phone     : ' . ($phoneOk ? "✔ {$phone}" : "✗ INVALID (raw: {$rawPhone}, cleaned: {$phone}) — must be 10 digits"));

        // Pincode check
        $pincode   = preg_replace('/\D/', '', (string) ($info?->pincode ?? ''));
        $pincodeOk = strlen($pincode) === 6;
        $this->line('  Pincode   : ' . ($pincodeOk ? "✔ {$pincode}" : "✗ INVALID (raw: {$info?->pincode}) — must be 6 digits"));

        // Items check
        $items = $order->items;
        $this->line('  Items     : ' . $items->count() . ' item(s)');
        foreach ($items as $item) {
            $product = $item->product;
            $sellerKyc = $product?->seller?->kycVerification;
            $pickup    = $sellerKyc?->shiprocket_pickup_location ?? $sellerKyc?->shiprocket_pickup_id ?? 'Default fallback';
            $this->line("    - {$product->name} | SKU: PROD-{$product->id} | qty: {$item->quantity} | price: ₹{$item->price} | pickup: {$pickup}");
        }

        if (!$phoneOk || !$pincodeOk) {
            $this->error('  → Cannot proceed — fix phone/pincode issues in the buyer profile first.');
            return;
        }

        // Dry-run: send the actual API call
        $this->line('  Sending create-order request to Shiprocket...');
        $service = new ShiprocketService();
        $result  = $service->createOrder($order, [
            'weight'  => 0.5,
            'length'  => 10,
            'breadth' => 10,
            'height'  => 10,
            'comment' => 'Diagnostic test — Order #' . $order->order_number,
        ]);

        if ($result) {
            $this->info('  ✔ Order created on Shiprocket!');
            $this->line('    shiprocket_order_id : ' . ($result['order_id']  ?? 'N/A'));
            $this->line('    shipment_id         : ' . ($result['shipment_id'] ?? 'N/A'));
            $this->line('    status              : ' . ($result['status'] ?? 'N/A'));
        } else {
            $this->error('  ✗ Order creation failed. Check storage/logs/laravel.log for "Shiprocket order creation failed".');
        }
    }
}
