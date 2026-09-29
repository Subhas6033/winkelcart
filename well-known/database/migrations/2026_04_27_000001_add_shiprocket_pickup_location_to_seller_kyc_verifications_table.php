<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShiprocketPickupLocationToSellerKycVerificationsTable extends Migration
{
    public function up()
    {
        Schema::table('seller_kyc_verifications', function (Blueprint $table) {
            $table->string('shiprocket_pickup_location')->nullable()->after('legal_name');
            $table->string('shiprocket_pickup_id')->nullable()->after('shiprocket_pickup_location');
        });
    }

    public function down()
    {
        Schema::table('seller_kyc_verifications', function (Blueprint $table) {
            $table->dropColumn(['shiprocket_pickup_location', 'shiprocket_pickup_id']);
        });
    }
}
