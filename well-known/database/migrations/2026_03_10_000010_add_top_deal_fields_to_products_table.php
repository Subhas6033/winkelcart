<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTopDealFieldsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'is_top_deal')) {
                $table->tinyInteger('is_top_deal')->default(0)->after('final_price');
            }

            if (!Schema::hasColumn('products', 'top_deal_priority')) {
                $table->integer('top_deal_priority')->default(0)->after('is_top_deal');
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
            if (Schema::hasColumn('products', 'top_deal_priority')) {
                $table->dropColumn('top_deal_priority');
            }

            if (Schema::hasColumn('products', 'is_top_deal')) {
                $table->dropColumn('is_top_deal');
            }
        });
    }
}
