<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds the fields required to register a Shiprocket pickup location via API
 * and to track whether the registration has been confirmed.
 *
 * Shiprocket requires: name, email, phone, address, city, state, country, pin_code
 * References: https://apidocs.shiprocket.in/#add-pickup-location
 */
class AddPickupAddressFieldsToSellerKycVerificationsTable extends Migration
{
    public function up(): void
    {
        Schema::table('seller_kyc_verifications', function (Blueprint $table) {
            // Pickup address fields used when registering the location with Shiprocket API
            if (!Schema::hasColumn('seller_kyc_verifications', 'pickup_address')) {
                $table->string('pickup_address', 300)->nullable()->after('shiprocket_pickup_id');
            }
            if (!Schema::hasColumn('seller_kyc_verifications', 'pickup_address_2')) {
                $table->string('pickup_address_2', 300)->nullable()->after('pickup_address');
            }
            if (!Schema::hasColumn('seller_kyc_verifications', 'pickup_city')) {
                $table->string('pickup_city', 100)->nullable()->after('pickup_address_2');
            }
            if (!Schema::hasColumn('seller_kyc_verifications', 'pickup_state')) {
                $table->string('pickup_state', 100)->nullable()->after('pickup_city');
            }
            if (!Schema::hasColumn('seller_kyc_verifications', 'pickup_pincode')) {
                $table->string('pickup_pincode', 10)->nullable()->after('pickup_state');
            }
            if (!Schema::hasColumn('seller_kyc_verifications', 'pickup_phone')) {
                $table->string('pickup_phone', 15)->nullable()->after('pickup_pincode');
            }
            if (!Schema::hasColumn('seller_kyc_verifications', 'pickup_email')) {
                $table->string('pickup_email', 190)->nullable()->after('pickup_phone');
            }
            // Tracks whether this seller's pickup location has been confirmed in Shiprocket.
            // Values: null (never synced), 'synced' (confirmed exists), 'failed' (last sync failed)
            if (!Schema::hasColumn('seller_kyc_verifications', 'pickup_sync_status')) {
                $table->string('pickup_sync_status', 20)->nullable()->after('pickup_email');
            }
            if (!Schema::hasColumn('seller_kyc_verifications', 'pickup_synced_at')) {
                $table->timestamp('pickup_synced_at')->nullable()->after('pickup_sync_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('seller_kyc_verifications', function (Blueprint $table) {
            $table->dropColumn([
                'pickup_address',
                'pickup_address_2',
                'pickup_city',
                'pickup_state',
                'pickup_pincode',
                'pickup_phone',
                'pickup_email',
                'pickup_sync_status',
                'pickup_synced_at',
            ]);
        });
    }
}
