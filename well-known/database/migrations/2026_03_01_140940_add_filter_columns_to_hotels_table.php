<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFilterColumnsToHotelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->string('property_type')->default('hotel')->after('name'); // hotel, resort, villa, apartment, spa, apart-hotel
            $table->json('tags')->nullable()->after('amenities'); // luxury, beach, mountain, budget, family, honeymoon, business
            $table->decimal('min_price', 10, 2)->nullable()->after('rating'); // Minimum room price for filtering
            $table->decimal('max_price', 10, 2)->nullable()->after('min_price'); // Maximum room price for filtering
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn(['property_type', 'tags', 'min_price', 'max_price']);
        });
    }
}
