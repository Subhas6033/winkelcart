<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWeightToProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Weight in kg; Shiprocket minimum is 0.5 kg
            $table->decimal('weight', 8, 3)->default(0.500)->after('final_price')
                  ->comment('Product weight in kg used for shipping calculation');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('weight');
        });
    }
}
