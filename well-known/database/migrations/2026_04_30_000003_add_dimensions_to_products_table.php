<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDimensionsToProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('length', 8, 2)->default(10.00)->after('weight')
                ->comment('Package length in cm for Shiprocket');
            $table->decimal('breadth', 8, 2)->default(10.00)->after('length')
                ->comment('Package breadth/width in cm for Shiprocket');
            $table->decimal('height', 8, 2)->default(10.00)->after('breadth')
                ->comment('Package height in cm for Shiprocket');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['length', 'breadth', 'height']);
        });
    }
}
