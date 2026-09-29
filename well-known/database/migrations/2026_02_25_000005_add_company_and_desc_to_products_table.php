<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyAndDescToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'company')) {
                $table->string('company', 255)->nullable()->after('category_id');
            }
            if (!Schema::hasColumn('products', 'desc')) {
                $table->text('desc')->nullable()->after('company');
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
            if (Schema::hasColumn('products', 'desc')) {
                $table->dropColumn('desc');
            }
            if (Schema::hasColumn('products', 'company')) {
                $table->dropColumn('company');
            }
        });
    }
}
