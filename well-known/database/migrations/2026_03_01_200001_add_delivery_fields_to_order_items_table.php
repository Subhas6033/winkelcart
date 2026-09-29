<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliveryFieldsToOrderItemsTable extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'delivery_date')) {
                $table->date('delivery_date')->nullable()->after('price');
            }
            if (!Schema::hasColumn('order_items', 'admin_paid_amount')) {
                $table->decimal('admin_paid_amount', 10, 2)->nullable()->after('delivery_date');
            }
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['delivery_date', 'admin_paid_amount']);
        });
    }
}
