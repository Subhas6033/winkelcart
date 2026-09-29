<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraFieldsToHotelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotels', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('hotels', 'email')) {
                $table->string('email')->nullable()->after('contact');
            }
            if (!Schema::hasColumn('hotels', 'website')) {
                $table->string('website')->nullable()->after('email');
            }
            if (!Schema::hasColumn('hotels', 'map_link')) {
                $table->string('map_link', 500)->nullable()->after('website');
            }
            if (!Schema::hasColumn('hotels', 'property_type')) {
                $table->string('property_type', 50)->default('hotel')->after('name');
            }
            if (!Schema::hasColumn('hotels', 'min_price')) {
                $table->decimal('min_price', 10, 2)->nullable()->after('rating');
            }
            if (!Schema::hasColumn('hotels', 'max_price')) {
                $table->decimal('max_price', 10, 2)->nullable()->after('min_price');
            }
            if (!Schema::hasColumn('hotels', 'checkin_time')) {
                $table->string('checkin_time', 10)->nullable()->after('desc');
            }
            if (!Schema::hasColumn('hotels', 'checkout_time')) {
                $table->string('checkout_time', 10)->nullable()->after('checkin_time');
            }
            if (!Schema::hasColumn('hotels', 'amenities')) {
                $table->json('amenities')->nullable()->after('checkout_time');
            }
            if (!Schema::hasColumn('hotels', 'tags')) {
                $table->json('tags')->nullable()->after('amenities');
            }
            if (!Schema::hasColumn('hotels', 'gallery')) {
                $table->json('gallery')->nullable()->after('image');
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
        Schema::table('hotels', function (Blueprint $table) {
            $columns = ['email', 'website', 'map_link', 'property_type', 'min_price', 'max_price', 'checkin_time', 'checkout_time', 'amenities', 'tags', 'gallery'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('hotels', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}
