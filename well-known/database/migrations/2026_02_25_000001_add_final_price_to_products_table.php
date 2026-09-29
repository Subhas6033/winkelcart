<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFinalPriceToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'final_price')) {
                // If 'offer_price' does not exist, add 'final_price' after 'name' as fallback
                $afterColumn = Schema::hasColumn('products', 'offer_price') ? 'offer_price' : 'name';
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
            $table->dropColumn('final_price');
        });
    }
}
