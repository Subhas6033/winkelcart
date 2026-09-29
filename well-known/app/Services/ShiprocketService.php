<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class ShiprocketService
{
    private string $baseUrl;
    private string $email;
    private string $password;
    private ?string $token = null;

    /** Holds the last human-readable error from createPickupLocation / syncSellerPickupLocation */
    public ?string $lastPickupError = null;

    public function __construct()
    {
        $this->email = config('services.shiprocket.email');
        $this->password = config('services.shiprocket.password');
        $this->baseUrl = config('services.shiprocket.base_url');
    }

    /**
     * Get authentication token for Shiprocket API.
     * Token is valid for 240 hours (10 days); cached for 9 days.
     *
     * @param bool $forceRefresh Skip cache and fetch a fresh token
     */
    public function getToken(bool $forceRefresh = false): ?string
    {
        if (!$forceRefresh && $this->token) {
            return $this->token;
        }

        // Try cache unless a force-refresh is requested
        if (!$forceRefresh) {
            $cachedToken = cache()->get('shiprocket_token');
            if ($cachedToken) {
                $this->token = $cachedToken;
                return $this->token;
            }
        }

        try {
            $response = Http::timeout(15)->post("{$this->baseUrl}/v1/external/auth/login", [
                'email' => $this->email,
                'password' => $this->password,
            ]);

            if ($response->successful() && $response->json('token')) {
                $this->token = $response->json('token');

                // Cache for 9 days (token valid for 10 days)
                cache()->put('shiprocket_token', $this->token, now()->addDays(9));

                return $this->token;
            }

            Log::error('Shiprocket authentication failed', [
                'status'   => $response->status(),
                'response' => $response->json(),
            ]);

            return null;
        } catch (Exception $e) {
            Log::error('Shiprocket token request failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Clear cached token (call when a 401 is received from any API endpoint).
     */
    private function clearCachedToken(): void
    {
        $this->token = null;
        cache()->forget('shiprocket_token');
    }

    /**
     * Execute an authenticated HTTP request, auto-retrying once on 401
     * by force-refreshing the token.
     *
     * @param callable $requestCallback  fn(string $token): \Illuminate\Http\Client\Response
     */
    private function authenticatedRequest(callable $requestCallback): ?\Illuminate\Http\Client\Response
    {
        $token = $this->getToken();
        if (!$token) {
            return null;
        }

        $response = $requestCallback($token);

        // On 401, the cached token is stale – refresh and retry once
        if ($response->status() === 401) {
            $this->clearCachedToken();
            $token = $this->getToken(forceRefresh: true);
            if (!$token) {
                return null;
            }
            $response = $requestCallback($token);
        }

        return $response;
    }

    /**
     * Check courier serviceability and get rates.
     *
     * @param string $pickupPincode   Seller's pincode
     * @param string $deliveryPincode Buyer's pincode
     * @param float  $weight          Package weight in kg
     * @param string $paymentMethod   'COD' or 'Pre-paid'
     * @return array|null Serviceability details or null on failure
     */
    public function checkServiceability(
        string $pickupPincode,
        string $deliveryPincode,
        float $weight = 0.5,
        string $paymentMethod = 'Pre-paid'
    ): ?array {
        try {
            $response = $this->authenticatedRequest(
                fn($token) => Http::withToken($token)
                    ->timeout(15)
                    ->get("{$this->baseUrl}/v1/external/courier/serviceability", [
                        'pickup_postcode'   => $pickupPincode,
                        'delivery_postcode' => $deliveryPincode,
                        'weight'            => $weight,
                        'cod'               => $paymentMethod === 'COD' ? 1 : 0,
                    ])
            );

            if (!$response) {
                return null;
            }

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Shiprocket serviceability check failed', [
                'status'   => $response->status(),
                'response' => $response->json(),
            ]);

            return null;
        } catch (Exception $e) {
            Log::error('Shiprocket serviceability request failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get the recommended (cheapest available) courier ID for a route.
     * Returns the numeric courier_company_id or null if serviceability fails.
     *
     * @param string $pickupPincode
     * @param string $deliveryPincode
     * @param float  $weight
     * @param string $paymentMethod 'COD' or 'Pre-paid'
     */
    public function getRecommendedCourierId(
        string $pickupPincode,
        string $deliveryPincode,
        float $weight = 0.5,
        string $paymentMethod = 'Pre-paid'
    ): ?int {
        $data = $this->checkServiceability($pickupPincode, $deliveryPincode, $weight, $paymentMethod);

        $companies = $data['data']['available_courier_companies'] ?? [];
        if (empty($companies)) {
            return null;
        }

        // Sort by rate ascending and return the cheapest available courier
        usort($companies, fn($a, $b) => ($a['rate'] ?? 0) <=> ($b['rate'] ?? 0));

        return (int) ($companies[0]['courier_company_id'] ?? 0) ?: null;
    }

    // ─── Pickup Location Management ──────────────────────────────────────────

    /**
     * Fetch all registered pickup locations from the Shiprocket panel.
     *
     * @return array List of pickup location objects from the API
     */
    public function getPickupLocations(): array
    {
        try {
            $response = $this->authenticatedRequest(
                fn($token) => Http::withToken($token)
                    ->timeout(15)
                    ->get("{$this->baseUrl}/v1/external/settings/company/pickup")
            );

            if (!$response || !$response->successful()) {
                Log::error('Shiprocket: could not fetch pickup locations', [
                    'status'   => $response?->status(),
                    'response' => $response?->json(),
                ]);
                return [];
            }

            return $response->json('data.shipping_address') ?? [];
        } catch (Exception $e) {
            Log::error('Shiprocket: getPickupLocations exception: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Check whether a pickup location NAME already exists in Shiprocket.
     * Comparison is case-insensitive to avoid common typo failures.
     *
     * @param string $name  The name to look for
     * @return array|null   The matching location data, or null if not found
     */
    public function findPickupLocation(string $name): ?array
    {
        $locations = $this->getPickupLocations();
        $needle    = strtolower(trim($name));

        foreach ($locations as $loc) {
            $haystack = strtolower(trim($loc['pickup_location'] ?? $loc['name'] ?? ''));
            if ($haystack === $needle) {
                return $loc;
            }
        }

        return null;
    }

    /**
     * Register a NEW pickup location with Shiprocket.
     *
     * Shiprocket required fields:
     *   pickup_location, name, email, phone, address, address_2, city, state, country, pin_code
     *
     * @param array $data  Array with keys: pickup_location, name, email, phone,
     *                     address, address_2, city, state, pin_code
     * @return array|null  API response or null on failure
     */
    public function createPickupLocation(array $data): ?array
    {
        // Validate mandatory fields before sending
        $required = ['pickup_location', 'name', 'email', 'phone', 'address', 'city', 'state', 'pin_code'];
        foreach ($required as $field) {
            if (empty(trim((string) ($data[$field] ?? '')))) {
                Log::error("Shiprocket createPickupLocation: missing required field '{$field}'", [
                    'data' => array_merge($data, ['phone' => '***REDACTED***', 'email' => '***REDACTED***']),
                ]);
                return null;
            }
        }

        // Normalize phone to 10 digits
        $phone = preg_replace('/\D/', '', (string) $data['phone']);
        if (strlen($phone) === 12 && str_starts_with($phone, '91')) {
            $phone = substr($phone, 2);
        }
        if (strlen($phone) !== 10) {
            Log::error("Shiprocket createPickupLocation: phone must be 10 digits", [
                'raw_phone' => $data['phone'],
            ]);
            return null;
        }

        $payload = [
            'pickup_location' => trim($data['pickup_location']),
            'name'            => trim($data['name']),
            'email'           => trim($data['email']),
            'phone'           => $phone,
            'address'         => trim($data['address']),
            'address_2'       => trim($data['address_2'] ?? ''),
            'city'            => trim($data['city']),
            'state'           => trim($data['state']),
            'country'         => 'India',
            'pin_code'        => preg_replace('/\D/', '', (string) $data['pin_code']),
        ];

        try {
            $response = $this->authenticatedRequest(
                fn($token) => Http::withToken($token)
                    ->timeout(30)
                    ->post("{$this->baseUrl}/v1/external/settings/company/addpickup", $payload)
            );

            if (!$response) {
                return null;
            }

            if ($response->successful()) {
                $result = $response->json();
                Log::info('Shiprocket pickup location created', [
                    'pickup_location' => $payload['pickup_location'],
                    'response'        => $result,
                ]);
                return $result;
            }

            Log::error('Shiprocket createPickupLocation failed', [
                'pickup_location' => $payload['pickup_location'],
                'http_status'     => $response->status(),
                'error_response'  => $response->json(),
            ]);

            // Extract the human-readable message Shiprocket sends back
            $raw = $response->json('message') ?? 'HTTP ' . $response->status();
            // Shiprocket sometimes returns a JSON-encoded string as the message
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                // e.g. {"address":["Address line 1 should have House no / Flat no / Road no."]}
                $parts = [];
                foreach ($decoded as $field => $msgs) {
                    $parts[] = $field . ': ' . implode('; ', (array) $msgs);
                }
                $readable = implode(' | ', $parts);
            } else {
                $readable = is_string($raw) ? $raw : json_encode($raw);
            }

            $this->lastPickupError = $readable;

            return null;
        } catch (Exception $e) {
            Log::error('Shiprocket createPickupLocation exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Ensure a seller's pickup location exists in Shiprocket.
     *
     * Flow:
     *  1. Check if the seller's `shiprocket_pickup_location` name already exists in Shiprocket.
     *  2. If found → confirm the exact name (updates KYC with canonical casing), mark synced.
     *  3. If NOT found → register it via the add-pickup API using the seller's KYC address fields,
     *     then re-verify it was created, mark synced.
     *  4. On any failure → mark sync status 'failed' so it can be retried.
     *
     * @param \App\Models\SellerKycVerification $kyc
     * @return bool  True if the pickup location is confirmed in Shiprocket
     */
    public function syncSellerPickupLocation(\App\Models\SellerKycVerification $kyc): bool
    {
        $desiredName = trim((string) ($kyc->shiprocket_pickup_location ?? ''));

        if (empty($desiredName)) {
            Log::error('Shiprocket syncSellerPickupLocation: shiprocket_pickup_location is empty', [
                'kyc_id' => $kyc->id,
            ]);
            $kyc->pickup_sync_status = 'failed';
            $kyc->save();
            return false;
        }

        // Step 1: Check if already registered
        $existing = $this->findPickupLocation($desiredName);

        if ($existing) {
            // Use the canonical name exactly as Shiprocket has it (preserves their casing)
            $canonicalName = $existing['pickup_location'] ?? $existing['name'] ?? $desiredName;
            $kyc->shiprocket_pickup_location = $canonicalName;
            $kyc->pickup_sync_status  = 'synced';
            $kyc->pickup_synced_at    = now();
            $kyc->save();

            Log::info('Shiprocket pickup location already exists — confirmed', [
                'kyc_id'          => $kyc->id,
                'pickup_location' => $canonicalName,
            ]);

            return true;
        }

        // Step 2: Not found — create it. We need address data.
        $seller = $kyc->seller;
        $sellerInfo = $seller?->user_info;

        // Build registration data from KYC pickup_* fields first,
        // falling back to seller's profile user_info
        $address  = $kyc->pickup_address  ?? $sellerInfo?->address_line_1 ?? $sellerInfo?->address ?? '';
        $address2 = $kyc->pickup_address_2 ?? $sellerInfo?->address_line_2 ?? '';
        $city     = $kyc->pickup_city      ?? $sellerInfo?->city            ?? '';
        $state    = $kyc->pickup_state     ?? $sellerInfo?->state           ?? '';
        $pincode  = $kyc->pickup_pincode   ?? $sellerInfo?->pincode         ?? '';
        $phone    = $kyc->pickup_phone     ?? $sellerInfo?->phone           ?? $seller?->mobile ?? '';
        $email    = $kyc->pickup_email     ?? $seller?->email               ?? '';
        $name     = $kyc->legal_name       ?? $seller?->name                ?? '';

        if (empty($address) || empty($city) || empty($state) || empty($pincode)) {
            Log::error('Shiprocket syncSellerPickupLocation: insufficient address data to register', [
                'kyc_id'   => $kyc->id,
                'address'  => $address,
                'city'     => $city,
                'state'    => $state,
                'pincode'  => $pincode,
            ]);
            $kyc->pickup_sync_status = 'failed';
            $kyc->save();
            return false;
        }

        $createResult = $this->createPickupLocation([
            'pickup_location' => $desiredName,
            'name'            => $name,
            'email'           => $email,
            'phone'           => $phone,
            'address'         => $address,
            'address_2'       => $address2,
            'city'            => $city,
            'state'           => $state,
            'pin_code'        => $pincode,
        ]);

        if (!$createResult) {
            $errMsg = $this->lastPickupError ?? 'Unknown error — check laravel.log';
            $kyc->pickup_sync_status = 'failed';
            $kyc->save();
            throw new \RuntimeException("Shiprocket pickup registration failed: {$errMsg}");
        }

        // Step 3: Verify it actually appears in the list now (Shiprocket can have propagation delay)
        $confirmed = $this->findPickupLocation($desiredName);
        $finalName = $confirmed
            ? ($confirmed['pickup_location'] ?? $confirmed['name'] ?? $desiredName)
            : $desiredName; // trust the name we sent if not yet visible

        $kyc->shiprocket_pickup_location = $finalName;
        $kyc->pickup_sync_status         = 'synced';
        $kyc->pickup_synced_at           = now();
        $kyc->save();

        Log::info('Shiprocket pickup location registered and confirmed', [
            'kyc_id'          => $kyc->id,
            'pickup_location' => $finalName,
        ]);

        return true;
    }

    /**
     * Create order on Shiprocket
     *
     * @param Order $order Laravel order model
     * @param array $shippingData Additional shipping data
     * @return array|null Shiprocket order data with order_id and shipment_id
     */
    public function createOrder(Order $order, array $shippingData = []): ?array
    {
        $token = $this->getToken();
        if (!$token) {
            return null;
        }

        $user = $order->buyer;
        $userInfo = $user->user_info;

        // Get first seller from order items for pickup address
        $firstItem = $order->items->first();
        $seller = $firstItem?->product?->seller;
        $sellerKyc = $seller?->kycVerification;

        // Determine payment method string for Shiprocket
        $paymentMethodStr = strtolower($order->payment_method) === 'cod' ? 'COD' : 'Pre-paid';

        // --- Normalize phone: Shiprocket requires exactly 10 digits ---
        $rawPhone = $userInfo?->phone ?? ($user->mobile ?? '');
        $phone = preg_replace('/\D/', '', $rawPhone);   // strip non-digits
        if (strlen($phone) === 12 && str_starts_with($phone, '91')) {
            $phone = substr($phone, 2); // strip country code 91
        } elseif (strlen($phone) === 13 && str_starts_with($phone, '+91')) {
            $phone = substr($phone, 3);
        }
        if (strlen($phone) !== 10) {
            Log::error('Shiprocket order creation aborted: invalid phone number', [
                'order_number' => $order->order_number,
                'raw_phone'    => $rawPhone,
                'cleaned'      => $phone,
            ]);
            return null;
        }

        // --- Normalize pincode: Shiprocket requires exactly 6 digits ---
        $pincode = preg_replace('/\D/', '', (string) ($userInfo?->pincode ?? ''));
        if (strlen($pincode) !== 6) {
            Log::error('Shiprocket order creation aborted: invalid billing pincode', [
                'order_number' => $order->order_number,
                'raw_pincode'  => $userInfo?->pincode,
            ]);
            return null;
        }

        // --- Determine pickup location name (must match Shiprocket panel exactly) ---
        $pickupLocation = config('services.shiprocket.pickup_location', 'Primary');
        if ($sellerKyc) {
            if (!empty($sellerKyc->shiprocket_pickup_location)) {
                $pickupLocation = $sellerKyc->shiprocket_pickup_location;
            } elseif (!empty($sellerKyc->shiprocket_pickup_id)) {
                $pickupLocation = $sellerKyc->shiprocket_pickup_id;
            }

            // If this seller's pickup location hasn't been confirmed in Shiprocket yet,
            // attempt an on-demand sync now so the order doesn't fail with an unknown location.
            if ($sellerKyc->pickup_sync_status !== 'synced') {
                Log::info('Shiprocket createOrder: pickup not yet synced, attempting on-demand sync', [
                    'order_number'    => $order->order_number,
                    'seller_id'       => $seller?->id,
                    'pickup_location' => $pickupLocation,
                ]);

                try {
                    $this->syncSellerPickupLocation($sellerKyc);
                    // Refresh the name in case sync corrected the casing
                    $pickupLocation = $sellerKyc->shiprocket_pickup_location ?: $pickupLocation;
                } catch (Exception $e) {
                    Log::error('Shiprocket createOrder: on-demand pickup sync failed: ' . $e->getMessage(), [
                        'order_number' => $order->order_number,
                    ]);
                }
            }
        }

        // Build order payload as per Shiprocket v1 API
        // Reference: https://apidocs.shiprocket.in/#create-order
        $orderData = [
            'order_id'              => (string) $order->order_number,
            'order_date'            => $order->created_at->format('Y-m-d H:i'),
            'pickup_location'       => $pickupLocation,
            'comment'               => $shippingData['comment'] ?? '',
            'billing_customer_name' => $userInfo?->full_name ?? $user->name,
            'billing_last_name'     => '',
            'billing_address'       => $userInfo?->address_line_1 ?? ($userInfo?->address ?? ''),
            'billing_address_2'     => $userInfo?->address_line_2 ?? '',
            'billing_city'          => $userInfo?->city ?? '',
            'billing_pincode'       => $pincode,
            'billing_state'         => $userInfo?->state ?? '',
            'billing_country'       => 'India',
            'billing_email'         => $user->email,
            'billing_phone'         => $phone,
            'shipping_is_billing'   => true,
            'order_items'           => [],
            'payment_method'        => $paymentMethodStr,
            'shipping_charges'      => 0,
            'giftwrap_charges'      => 0,
            'transaction_charges'   => 0,
            'total_discount'        => 0,
            // IMPORTANT: Shiprocket field is sub_total (not subtotal)
            'sub_total'             => (float) ($order->grand_total ?? $order->total_amount),
        ];

        // Dimensions & weight: prefer $shippingData, fall back to first product's stored values
        $firstProduct = $order->items->first()?->product;
        $orderData['length']  = (float) ($shippingData['length']  ?? $firstProduct?->length  ?? 10);
        $orderData['breadth'] = (float) ($shippingData['breadth'] ?? $firstProduct?->breadth ?? 10);
        $orderData['height']  = (float) ($shippingData['height']  ?? $firstProduct?->height  ?? 10);
        $orderData['weight']  = (float) ($shippingData['weight']  ?? max(
            $order->items->sum(fn($i) => floatval($i->product?->weight ?? 0.5) * $i->quantity),
            0.5
        ));

        // channel_id is optional; only add if configured as a numeric value in .env
        $channelId = config('services.shiprocket.channel_id');
        if ($channelId && is_numeric($channelId)) {
            $orderData['channel_id'] = (int) $channelId;
        }

        // Add order items
        // IMPORTANT: Shiprocket uses 'units' and 'selling_price' (NOT 'quantity'/'price')
        foreach ($order->items as $item) {
            $product = $item->product;
            try {
                $productUrl = route('product.show', $product->id);
            } catch (\Exception $e) {
                $productUrl = config('app.url') . '/product/' . $product->id;
            }
            $orderData['order_items'][] = [
                'name'          => $product->name,
                'sku'           => 'PROD-' . $product->id,
                'units'         => (int) $item->quantity,  // Shiprocket: 'units' not 'quantity'
                'selling_price' => (float) $item->price,   // Shiprocket: 'selling_price' not 'price'
                'discount'      => 0,
                'tax'           => 0,
                'hsn'           => $shippingData['hsn'] ?? '',
            ];
        }

        try {
            $response = $this->authenticatedRequest(
                fn($token) => Http::withToken($token)
                    ->timeout(30)
                    ->post("{$this->baseUrl}/v1/external/orders/create/adhoc", $orderData)
            );

            if (!$response) {
                Log::error('Shiprocket order creation: no response (auth failed)', [
                    'order_number' => $order->order_number,
                ]);
                return null;
            }

            if ($response->successful()) {
                $result = $response->json();

                Log::info('Shiprocket order created successfully', [
                    'order_number'       => $order->order_number,
                    'shiprocket_order_id' => $result['order_id'] ?? null,
                    'shipment_id'        => $result['shipment_id'] ?? null,
                    'status'             => $result['status'] ?? null,
                ]);

                return $result;
            }

            // Log the FULL error payload from Shiprocket so you can see the exact reason
            Log::error('Shiprocket order creation failed', [
                'order_number'   => $order->order_number,
                'http_status'    => $response->status(),
                'error_response' => $response->json(),
                'payload_sent'   => array_merge($orderData, ['billing_phone' => '***REDACTED***']),
            ]);

            return null;
        } catch (Exception $e) {
            Log::error('Shiprocket order creation exception: ' . $e->getMessage(), [
                'order_number' => $order->order_number,
                'trace'        => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    /**
     * Assign AWB to shipment.
     *
     * @param int      $shipmentId Shiprocket shipment ID
     * @param int|null $courierId  Courier company ID from serviceability API.
     *                             Pass null to let Shiprocket auto-assign the best courier.
     * @return array|null Response data or null on failure
     */
    public function assignAwb(int $shipmentId, ?int $courierId = null): ?array
    {
        try {
            $payload = ['shipment_id' => $shipmentId];
            if ($courierId !== null) {
                $payload['courier_id'] = $courierId;
            }

            $response = $this->authenticatedRequest(
                fn($token) => Http::withToken($token)
                    ->timeout(30)
                    ->post("{$this->baseUrl}/v1/external/courier/assign/awb", $payload)
            );

            if (!$response) {
                return null;
            }

            if ($response->successful()) {
                $result = $response->json();

                Log::info('AWB assigned successfully', [
                    'shipment_id'  => $shipmentId,
                    'courier_id'   => $courierId,
                    'full_response' => $result,
                    'awb'          => $result['awb_code'] ?? ($result['response']['data']['awb_code'] ?? null),
                ]);

                return $result;
            }

            Log::error('AWB assignment failed', [
                'shipment_id'    => $shipmentId,
                'courier_id'     => $courierId,
                'http_status'    => $response->status(),
                'error_response' => $response->json(),
            ]);

            return null;
        } catch (Exception $e) {
            Log::error('AWB assignment failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Request pickup for shipment
     *
     * @param int $shipmentId Shiprocket shipment ID
     * @param array $pickupDates Optional array of pickup dates ['YYYY-MM-DD']
     * @return array|null Response data or null on failure
     */
    public function requestPickup(int $shipmentId, array $pickupDates = []): ?array
    {
        try {
            $payload = ['shipment_id' => [$shipmentId]];

            if (!empty($pickupDates)) {
                $payload['pickup_date'] = $pickupDates;
            }

            $response = $this->authenticatedRequest(
                fn($token) => Http::withToken($token)
                    ->timeout(30)
                    ->post("{$this->baseUrl}/v1/external/courier/generate/pickup", $payload)
            );

            if (!$response) {
                return null;
            }

            if ($response->successful()) {
                $result = $response->json();

                Log::info('Pickup requested successfully', [
                    'shipment_id' => $shipmentId,
                ]);

                return $result;
            }

            Log::error('Pickup request failed', [
                'shipment_id'    => $shipmentId,
                'http_status'    => $response->status(),
                'error_response' => $response->json(),
            ]);

            return null;
        } catch (Exception $e) {
            Log::error('Pickup request failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate shipping label
     *
     * @param array $shipmentIds Array of shipment IDs
     * @return string|null PDF URL or null on failure
     */
    public function generateLabel(array $shipmentIds): ?string
    {
        $token = $this->getToken();
        if (!$token) {
            return null;
        }

        try {
            $response = Http::withToken($token)
                ->timeout(30)
                ->post("{$this->baseUrl}/v1/external/courier/generate/label", [
                    'shipment_id' => $shipmentIds,
                ]);

            if ($response->successful()) {
                $result = $response->json();
                $labelUrl = $result['label_url'] ?? null;

                if ($labelUrl) {
                    Log::info('Label generated successfully', [
                        'shipment_ids' => implode(',', $shipmentIds),
                        'url' => $labelUrl,
                    ]);
                }

                return $labelUrl;
            }

            Log::error('Label generation failed', [
                'shipment_ids' => implode(',', $shipmentIds),
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            return null;
        } catch (Exception $e) {
            Log::error('Label generation failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate shipping invoice
     *
     * @param array $shipmentIds Array of shipment IDs
     * @return string|null PDF URL or null on failure
     */
    public function generateInvoice(array $orderIds): ?string
    {
        $token = $this->getToken();
        if (!$token) {
            return null;
        }

        try {
            // Shiprocket invoice endpoint requires `ids` = array of SHIPROCKET ORDER IDs (not shipment IDs)
            $response = Http::withToken($token)
                ->timeout(30)
                ->post("{$this->baseUrl}/v1/external/orders/print/invoice", [
                    'ids' => $orderIds,
                ]);

            if ($response->successful()) {
                $result = $response->json();
                $invoiceUrl = $result['invoice_url'] ?? null;

                if ($invoiceUrl) {
                    Log::info('Invoice generated successfully', [
                        'order_ids' => implode(',', $orderIds),
                        'url' => $invoiceUrl,
                    ]);
                }

                return $invoiceUrl;
            }

            Log::error('Invoice generation failed', [
                'order_ids' => implode(',', $orderIds),
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            return null;
        } catch (Exception $e) {
            Log::error('Invoice generation failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Track shipment by AWB code
     *
     * @param string $awbCode AWB tracking number
     * @return array|null Tracking details or null on failure
     */
    public function trackShipment(string $awbCode): ?array
    {
        $token = $this->getToken();
        if (!$token) {
            return null;
        }

        try {
            $response = Http::withToken($token)
                ->timeout(30)
                ->get("{$this->baseUrl}/v1/external/courier/track/awb/{$awbCode}");

            if ($response->successful()) {
                $result = $response->json();

                Log::info('Shipment tracked successfully', [
                    'awb_code' => $awbCode,
                    'status' => $result['tracking_status'] ?? null,
                ]);

                return $result;
            }

            Log::error('Shipment tracking failed', [
                'awb_code' => $awbCode,
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            return null;
        } catch (Exception $e) {
            Log::error('Shipment tracking failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get order by Shiprocket order ID
     *
     * @param int $shiprocketOrderId Shiprocket order ID
     * @return array|null Order details or null on failure
     */
    public function getOrder(int $shiprocketOrderId): ?array
    {
        $token = $this->getToken();
        if (!$token) {
            return null;
        }

        try {
            $response = Http::withToken($token)
                ->timeout(30)
                ->get("{$this->baseUrl}/v1/external/orders/show/{$shiprocketOrderId}");

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (Exception $e) {
            Log::error('Get order failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Process full shipment flow for an order
     * 1. Create order
     * 2. Assign AWB
     * 3. Request pickup
     * 4. Generate label and invoice
     *
     * @param Order $order Laravel order model
     * @param array $shippingData Additional shipping data
     * @return array Result with success status and data
     */
    public function processShipment(Order $order, array $shippingData = []): array
    {
        $result = [
            'success'      => false,
            'order_id'     => null,   // Shiprocket order ID (used for invoice)
            'shipment_id'  => null,
            'awb_code'     => null,
            'label_url'    => null,
            'invoice_url'  => null,
            'errors'       => [],
        ];

        // ── Step 1: Create order ──────────────────────────────────────────────
        $orderResponse = $this->createOrder($order, $shippingData);
        if (!$orderResponse) {
            $result['errors'][] = 'Failed to create Shiprocket order';
            return $result;
        }

        $result['order_id']    = $orderResponse['order_id']    ?? null;  // Shiprocket order ID
        $result['shipment_id'] = $orderResponse['shipment_id'] ?? null;

        if (empty($result['shipment_id'])) {
            $result['errors'][] = 'Shipment ID not received from Shiprocket';
            return $result;
        }

        // ── Step 2: Select courier via serviceability, then assign AWB ────────
        // Shiprocket requires a courier_id to actually assign an AWB.
        // Without it the AWB call returns awb:null.
        $courierId = null;
        try {
            $userInfo        = $order->buyer?->user_info;
            $deliveryPincode = preg_replace('/\D/', '', (string) ($userInfo?->pincode ?? ''));
            $paymentMethod   = strtolower($order->payment_method ?? '') === 'cod' ? 'COD' : 'Pre-paid';

            // Derive pickup pincode: prefer KYC field, fall back to seller user_info
            $firstItem       = $order->items->first();
            $sellerKyc       = $firstItem?->product?->seller?->kycVerification;
            $pickupPincode   = preg_replace('/\D/', '',
                (string) ($sellerKyc?->pickup_pincode
                    ?? $firstItem?->product?->seller?->user_info?->pincode
                    ?? '')
            );

            // Estimate total weight (kg) from product fields; default 0.5 kg per item
            $weight = empty($shippingData['weight'])
                ? $order->items->sum(fn($item) => floatval($item->product?->weight ?? 0.5) * $item->quantity)
                : (float) $shippingData['weight'];
            $weight = max(round($weight, 3), 0.5);

            if (strlen($pickupPincode) === 6 && strlen($deliveryPincode) === 6) {
                $courierId = $this->getRecommendedCourierId(
                    $pickupPincode,
                    $deliveryPincode,
                    $weight,
                    $paymentMethod
                );

                if ($courierId) {
                    Log::info('Courier selected for shipment', [
                        'order_number' => $order->order_number,
                        'courier_id'   => $courierId,
                        'weight_kg'    => $weight,
                    ]);
                } else {
                    Log::warning('No courier found via serviceability — attempting auto-assign', [
                        'order_number'    => $order->order_number,
                        'pickup_pincode'  => $pickupPincode,
                        'delivery_pincode'=> $deliveryPincode,
                    ]);
                }
            }
        } catch (Exception $e) {
            Log::warning('Courier serviceability lookup failed: ' . $e->getMessage(), [
                'order_number' => $order->order_number,
            ]);
        }

        $awbResponse = $this->assignAwb($result['shipment_id'], $courierId);
        if (!$awbResponse) {
            $result['errors'][] = 'Failed to assign AWB';
            return $result;
        }

        // Shiprocket nests AWB under response.data.awb_code
        $awbCode = $awbResponse['awb_code']
            ?? ($awbResponse['response']['data']['awb_code'] ?? null);

        // AWB assignment can be slightly async — poll once after a short wait
        if (empty($awbCode) && !empty($result['order_id'])) {
            Log::info('AWB not yet assigned, waiting 4s then polling order status', [
                'shipment_id' => $result['shipment_id'],
            ]);
            sleep(4);
            $orderDetail = $this->getOrder($result['order_id']);
            $awbCode     = $orderDetail['data']['awb_code']
                ?? ($orderDetail['awb_code'] ?? null);
        }

        $result['awb_code'] = $awbCode ?: null;

        Log::info('AWB assignment result', [
            'order_number' => $order->order_number,
            'shipment_id'  => $result['shipment_id'],
            'courier_id'   => $courierId,
            'awb_code'     => $result['awb_code'],
        ]);

        // ── Step 3: Request pickup ────────────────────────────────────────────
        if (!empty($result['awb_code'])) {
            $pickupResponse = $this->requestPickup($result['shipment_id']);
            if (!$pickupResponse) {
                $result['errors'][] = 'Failed to request pickup (AWB assigned — shipment still created)';
            }
        } else {
            $result['errors'][] = 'AWB not assigned — pickup skipped. Retry once AWB appears in Shiprocket.';
            Log::warning('Skipping pickup request: AWB is null', [
                'order_number' => $order->order_number,
                'shipment_id'  => $result['shipment_id'],
            ]);
        }

        // ── Step 4: Label (needs AWB) ─────────────────────────────────────────
        if (!empty($result['awb_code'])) {
            $labelUrl = $this->generateLabel([$result['shipment_id']]);
            if ($labelUrl) {
                $result['label_url'] = $labelUrl;
            }
        }

        // ── Step 5: Invoice (needs Shiprocket ORDER ID, not shipment ID) ──────
        if (!empty($result['order_id'])) {
            $invoiceUrl = $this->generateInvoice([$result['order_id']]);
            if ($invoiceUrl) {
                $result['invoice_url'] = $invoiceUrl;
            }
        }

        $result['success'] = true;

        Log::info('Shiprocket shipment processed successfully', [
            'order_number' => $order->order_number,
            'shipment_id'  => $result['shipment_id'],
            'awb_code'     => $result['awb_code'],
            'label_url'    => $result['label_url'] ? 'generated' : 'pending',
            'invoice_url'  => $result['invoice_url'] ? 'generated' : 'pending',
        ]);

        return $result;
    }

    // ─── Dynamic Shipping Cost Calculation ───────────────────────────────────

    /**
     * Free shipping threshold (INR). Override via config('services.shiprocket.free_shipping_threshold').
     */
    public const FREE_SHIPPING_THRESHOLD = 999.0;

    /**
     * Minimum weight (kg) Shiprocket will accept.
     */
    public const MIN_WEIGHT_KG = 0.5;

    /**
     * Calculate shipping costs for a collection of cart items grouped by seller.
     *
     * Returns an array with:
     *  - 'total_shipping'   float   Total shipping cost across all sellers
     *  - 'cart_subtotal'    float   Sum of (price × qty) for all items
     *  - 'grand_total'      float   cart_subtotal + total_shipping
     *  - 'free_shipping'    bool    Whether free-shipping threshold is met
     *  - 'sellers'          array   Per-seller breakdown (see below)
     *  - 'no_service'       bool    True if ANY seller has no courier available
     *  - 'error'            string|null  Human-readable error if calculation failed
     *
     * Per-seller breakdown entry:
     *  - 'seller_id'           int
     *  - 'seller_name'         string
     *  - 'pickup_pincode'      string
     *  - 'weight_kg'           float
     *  - 'shipping_cost'       float    Cheapest available rate
     *  - 'available_couriers'  array    All courier options [{name, rate, etd, courier_company_id}]
     *  - 'no_service'          bool
     *  - 'error'               string|null
     *
     * @param \Illuminate\Support\Collection $cartItems   Cart model collection (with products.seller.kycVerification loaded)
     * @param string                         $deliveryPincode  Buyer's pincode (6 digits)
     * @param string                         $paymentMethod    'cod' or 'razorpay'/'prepaid'
     * @return array
     */
    public function calculateCartShipping(
        \Illuminate\Support\Collection $cartItems,
        string $deliveryPincode,
        string $paymentMethod = 'razorpay'
    ): array {
        $deliveryPincode = preg_replace('/\D/', '', $deliveryPincode);
        $isCod           = strtolower($paymentMethod) === 'cod';
        $codFlag         = $isCod ? 'COD' : 'Pre-paid';

        // Calculate cart subtotal
        $cartSubtotal = $cartItems->sum(function ($item) {
            $product = $item->products ?? $item->product ?? null;
            if (!$product) {
                return 0;
            }
            return (float) ($product->final_price ?? $product->offer_price ?? $product->total_price ?? $item->price ?? 0)
                * (int) $item->quantity;
        });

        // Free shipping check
        $freeShippingThreshold = (float) config(
            'services.shiprocket.free_shipping_threshold',
            self::FREE_SHIPPING_THRESHOLD
        );
        if ($cartSubtotal >= $freeShippingThreshold) {
            return [
                'total_shipping'  => 0.0,
                'cart_subtotal'   => $cartSubtotal,
                'grand_total'     => $cartSubtotal,
                'free_shipping'   => true,
                'sellers'         => [],
                'no_service'      => false,
                'error'           => null,
            ];
        }

        // Guard: need a valid 6-digit delivery pincode
        if (strlen($deliveryPincode) !== 6) {
            return [
                'total_shipping'  => 0.0,
                'cart_subtotal'   => $cartSubtotal,
                'grand_total'     => $cartSubtotal,
                'free_shipping'   => false,
                'sellers'         => [],
                'no_service'      => false,
                'error'           => 'Invalid delivery pincode. Please update your address.',
            ];
        }

        // Group cart items by seller ID
        $grouped = [];
        foreach ($cartItems as $item) {
            $product  = $item->products ?? $item->product ?? null;
            $sellerId = $product?->created_by ?? 0;
            if (!isset($grouped[$sellerId])) {
                $grouped[$sellerId] = [
                    'seller'   => $product?->seller,
                    'items'    => collect(),
                ];
            }
            $grouped[$sellerId]['items']->push($item);
        }

        $totalShipping = 0.0;
        $sellerResults = [];
        $anyNoService  = false;

        foreach ($grouped as $sellerId => $group) {
            $seller    = $group['seller'];
            $sellerKyc = $seller?->kycVerification;

            // Derive pickup pincode
            $pickupPincode = preg_replace('/\D/', '',
                (string) (
                    $sellerKyc?->pickup_pincode
                    ?? $seller?->user_info?->pincode
                    ?? ''
                )
            );

            // Calculate total weight for this seller's items
            $weight = $group['items']->sum(function ($item) {
                $product = $item->products ?? $item->product ?? null;
                return (float) ($product?->weight ?? self::MIN_WEIGHT_KG) * (int) $item->quantity;
            });
            $weight = max(round($weight, 3), self::MIN_WEIGHT_KG);

            $sellerEntry = [
                'seller_id'          => $sellerId,
                'seller_name'        => $seller?->name ?? 'Unknown Seller',
                'pickup_pincode'     => $pickupPincode,
                'weight_kg'          => $weight,
                'shipping_cost'      => 0.0,
                'available_couriers' => [],
                'no_service'         => false,
                'error'              => null,
            ];

            // If pickup pincode is missing/invalid, skip API call (treat as 0 cost)
            if (strlen($pickupPincode) !== 6) {
                $sellerEntry['error'] = 'Seller pickup address incomplete — shipping rate unavailable.';
                $sellerResults[]      = $sellerEntry;
                Log::warning('ShiprocketService::calculateCartShipping: missing pickup pincode', [
                    'seller_id' => $sellerId,
                ]);
                continue;
            }

            // Cache key: unique per seller + delivery + weight (rounded) + cod
            $cacheKey = 'ship_rate:' . md5("{$pickupPincode}:{$deliveryPincode}:{$weight}:{$codFlag}");
            $serviceData = cache()->remember($cacheKey, now()->addMinutes(7), function () use (
                $pickupPincode, $deliveryPincode, $weight, $codFlag
            ) {
                return $this->checkServiceability($pickupPincode, $deliveryPincode, $weight, $codFlag);
            });

            if (!$serviceData) {
                $sellerEntry['error']      = 'Shipping rate check failed (API error). Please try again.';
                $sellerEntry['no_service'] = true;
                $anyNoService              = true;
                $sellerResults[]           = $sellerEntry;
                continue;
            }

            $companies = $serviceData['data']['available_courier_companies'] ?? [];

            if (empty($companies)) {
                $sellerEntry['no_service'] = true;
                $sellerEntry['error']      = 'No courier available for this route.';
                $anyNoService              = true;
                $sellerResults[]           = $sellerEntry;
                continue;
            }

            // Sort couriers by rate ascending (cheapest first)
            usort($companies, fn($a, $b) => ($a['rate'] ?? 0) <=> ($b['rate'] ?? 0));

            // Build available courier options list
            $courierOptions = array_map(fn($c) => [
                'courier_company_id' => $c['courier_company_id'] ?? null,
                'name'               => $c['courier_name']        ?? ($c['name'] ?? 'Courier'),
                'rate'               => (float) ($c['rate']       ?? 0),
                'etd'                => $c['etd']                  ?? ($c['estimated_delivery_days'] ?? null),
            ], $companies);

            $cheapest = $courierOptions[0];

            $sellerEntry['shipping_cost']      = $cheapest['rate'];
            $sellerEntry['available_couriers'] = $courierOptions;

            $totalShipping += $cheapest['rate'];
            $sellerResults[] = $sellerEntry;
        }

        return [
            'total_shipping'  => round($totalShipping, 2),
            'cart_subtotal'   => round($cartSubtotal, 2),
            'grand_total'     => round($cartSubtotal + $totalShipping, 2),
            'free_shipping'   => false,
            'sellers'         => $sellerResults,
            'no_service'      => $anyNoService,
            'error'           => null,
        ];
    }

    /**
     * Calculate shipping for a single product (Buy Now flow).
     *
     * @param \App\Models\Product $product
     * @param int                 $quantity
     * @param string              $deliveryPincode
     * @param string              $paymentMethod  'cod' or 'razorpay'
     * @return array  Same structure as calculateCartShipping()
     */
    public function calculateSingleProductShipping(
        \App\Models\Product $product,
        int $quantity,
        string $deliveryPincode,
        string $paymentMethod = 'razorpay'
    ): array {
        // Build a synthetic cart-like collection so we can reuse calculateCartShipping
        $fakeItem = new \stdClass();
        $fakeItem->quantity = $quantity;
        $fakeItem->product  = $product;
        $fakeItem->products = $product;
        $fakeItem->price    = (float) ($product->final_price ?? $product->offer_price ?? $product->total_price ?? 0);

        return $this->calculateCartShipping(collect([$fakeItem]), $deliveryPincode, $paymentMethod);
    }
}
