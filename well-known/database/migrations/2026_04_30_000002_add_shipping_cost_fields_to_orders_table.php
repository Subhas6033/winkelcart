<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShippingCostFieldsToOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // cart_total  = sum of (item price × qty) before shipping
            $table->decimal('cart_total', 10, 2)->nullable()->after('total_amount')
                  ->comment('Cart subtotal before shipping');

            // shipping_cost = total shipping charged (sum across all sellers)
            $table->decimal('shipping_cost', 10, 2)->default(0)->after('cart_total')
                  ->comment('Total shipping cost added at checkout');

            // grand_total = cart_total + shipping_cost
            $table->decimal('grand_total', 10, 2)->nullable()->after('shipping_cost')
                  ->comment('Grand total = cart_total + shipping_cost');

            // Per-seller shipping breakdown (JSON)
            $table->json('shipping_breakdown')->nullable()->after('grand_total')
                  ->comment('Seller-wise shipping cost breakdown as JSON');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['cart_total', 'shipping_cost', 'grand_total', 'shipping_breakdown']);
        });
    }
}
