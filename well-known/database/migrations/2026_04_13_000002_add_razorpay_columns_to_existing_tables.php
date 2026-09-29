<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add Razorpay columns to orders table
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'razorpay_order_id')) {
                $table->string('razorpay_order_id')->nullable()->after('payment_image');
            }
            if (!Schema::hasColumn('orders', 'razorpay_payment_id')) {
                $table->string('razorpay_payment_id')->nullable()->after('razorpay_order_id');
            }
            if (!Schema::hasColumn('orders', 'razorpay_signature')) {
                $table->string('razorpay_signature')->nullable()->after('razorpay_payment_id');
            }
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status', 30)->default('Pending')->after('razorpay_signature');
            }
        });

        // Add Razorpay columns to bookings table
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'razorpay_order_id')) {
                $table->string('razorpay_order_id')->nullable()->after('booking_code');
            }
            if (!Schema::hasColumn('bookings', 'razorpay_payment_id')) {
                $table->string('razorpay_payment_id')->nullable()->after('razorpay_order_id');
            }
            if (!Schema::hasColumn('bookings', 'payment_status')) {
                $table->string('payment_status', 30)->default('Pending')->after('razorpay_payment_id');
            }
        });

        // Add Razorpay columns to seller_kyc_verifications table
        Schema::table('seller_kyc_verifications', function (Blueprint $table) {
            if (!Schema::hasColumn('seller_kyc_verifications', 'razorpay_payment_id')) {
                $table->string('razorpay_payment_id')->nullable()->after('membership_payment_verified');
            }
            if (!Schema::hasColumn('seller_kyc_verifications', 'razorpay_order_id')) {
                $table->string('razorpay_order_id')->nullable()->after('razorpay_payment_id');
            }
            if (!Schema::hasColumn('seller_kyc_verifications', 'razorpay_signature')) {
                $table->string('razorpay_signature')->nullable()->after('razorpay_order_id');
            }
            if (!Schema::hasColumn('seller_kyc_verifications', 'payment_status')) {
                $table->string('payment_status', 30)->nullable()->after('razorpay_signature');
            }
            if (!Schema::hasColumn('seller_kyc_verifications', 'membership_start')) {
                $table->timestamp('membership_start')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('seller_kyc_verifications', 'membership_expiry')) {
                $table->timestamp('membership_expiry')->nullable()->after('membership_start');
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature', 'payment_status']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['razorpay_order_id', 'razorpay_payment_id', 'payment_status']);
        });

        Schema::table('seller_kyc_verifications', function (Blueprint $table) {
            $table->dropColumn(['razorpay_payment_id', 'razorpay_order_id', 'razorpay_signature', 'payment_status', 'membership_start', 'membership_expiry']);
        });
    }
};
