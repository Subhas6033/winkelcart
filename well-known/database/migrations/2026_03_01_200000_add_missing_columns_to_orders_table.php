<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'status')) {
                $table->tinyInteger('status')->default(0)->after('order_status'); // 0 = active, 1 = inactive
            }
            if (!Schema::hasColumn('orders', 'deleted')) {
                $table->tinyInteger('deleted')->default(0)->after('status'); // 0 = not deleted, 1 = deleted
            }
            if (!Schema::hasColumn('orders', 'payment_image')) {
                $table->string('payment_image')->nullable()->after('shipping_address');
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['status', 'deleted', 'payment_image']);
        });
    }
}
