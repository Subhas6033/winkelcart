<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAllProductPricingFieldsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'total_price')) {
                $table->decimal('total_price', 10, 2)->nullable()->after('name');
            }
            if (!Schema::hasColumn('products', 'offer_price')) {
                $table->decimal('offer_price', 10, 2)->nullable()->after('total_price');
            }
            if (!Schema::hasColumn('products', 'tax')) {
                $table->decimal('tax', 5, 2)->nullable()->after('offer_price');
            }
            if (!Schema::hasColumn('products', 'final_price')) {
                $table->decimal('final_price', 10, 2)->nullable()->after('tax');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'final_price')) {
                $table->dropColumn('final_price');
            }
            if (Schema::hasColumn('products', 'tax')) {
                $table->dropColumn('tax');
            }
            if (Schema::hasColumn('products', 'offer_price')) {
                $table->dropColumn('offer_price');
            }
            if (Schema::hasColumn('products', 'total_price')) {
                $table->dropColumn('total_price');
            }
        });
    }
}
