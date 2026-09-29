<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOfferPriceTaxFinalPriceToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Add offer_price after total_price if it exists, otherwise after name
            if (!Schema::hasColumn('products', 'offer_price')) {
                $afterColumn = Schema::hasColumn('products', 'total_price') ? 'total_price' : 'name';
                $table->decimal('offer_price', 10, 2)->nullable()->after($afterColumn);
            }
            if (!Schema::hasColumn('products', 'tax')) {
                $afterColumn = Schema::hasColumn('products', 'offer_price') ? 'offer_price' : (Schema::hasColumn('products', 'total_price') ? 'total_price' : 'name');
                $table->decimal('tax', 5, 2)->nullable()->after($afterColumn);
            }
            if (!Schema::hasColumn('products', 'final_price')) {
                $afterColumn = Schema::hasColumn('products', 'tax') ? 'tax' : (Schema::hasColumn('products', 'offer_price') ? 'offer_price' : (Schema::hasColumn('products', 'total_price') ? 'total_price' : 'name'));
                $table->decimal('final_price', 10, 2)->nullable()->after($afterColumn);
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
        });
    }
}
