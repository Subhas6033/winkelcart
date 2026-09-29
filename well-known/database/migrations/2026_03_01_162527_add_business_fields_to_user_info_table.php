<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBusinessFieldsToUserInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_info', function (Blueprint $table) {
            $table->unsignedBigInteger('business_category_id')->nullable()->after('address');
            $table->string('gst_no', 20)->nullable()->after('business_category_id');
            $table->unsignedBigInteger('state_id')->nullable()->after('gst_no');
            $table->unsignedBigInteger('country_id')->nullable()->after('state_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_info', function (Blueprint $table) {
            $table->dropColumn(['business_category_id', 'gst_no', 'state_id', 'country_id']);
        });
    }
}
