<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShiprocketFieldsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Shiprocket order and shipment IDs
            $table->string('shiprocket_order_id')->nullable()->after('payment_status');
            $table->string('shiprocket_shipment_id')->nullable()->after('shiprocket_order_id');

            // AWB and courier details
            $table->string('awb_code')->nullable()->after('shiprocket_shipment_id');
            $table->string('courier_name')->nullable()->after('awb_code');

            // Document URLs
            $table->string('shipping_label_url')->nullable()->after('courier_name');
            $table->string('shipping_invoice_url')->nullable()->after('shipping_label_url');

            // Tracking status
            $table->string('tracking_status')->nullable()->after('shipping_invoice_url');
            $table->text('tracking_details')->nullable()->after('tracking_status');

            // Pickup status
            $table->boolean('pickup_requested')->default(false)->after('tracking_details');
            $table->timestamp('pickup_requested_at')->nullable()->after('pickup_requested');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shiprocket_order_id',
                'shiprocket_shipment_id',
                'awb_code',
                'courier_name',
                'shipping_label_url',
                'shipping_invoice_url',
                'tracking_status',
                'tracking_details',
                'pickup_requested',
                'pickup_requested_at',
            ]);
        });
    }
}
