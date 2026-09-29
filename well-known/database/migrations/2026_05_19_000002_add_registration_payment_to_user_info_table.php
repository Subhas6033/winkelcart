<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_info', function (Blueprint $table) {
            if (!Schema::hasColumn('user_info', 'registration_razorpay_payment_id')) {
                $table->string('registration_razorpay_payment_id')->nullable()->after('subscription_plan');
            }
            if (!Schema::hasColumn('user_info', 'registration_razorpay_order_id')) {
                $table->string('registration_razorpay_order_id')->nullable()->after('registration_razorpay_payment_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_info', function (Blueprint $table) {
            if (Schema::hasColumn('user_info', 'registration_razorpay_payment_id')) {
                $table->dropColumn('registration_razorpay_payment_id');
            }
            if (Schema::hasColumn('user_info', 'registration_razorpay_order_id')) {
                $table->dropColumn('registration_razorpay_order_id');
            }
        });
    }
};
