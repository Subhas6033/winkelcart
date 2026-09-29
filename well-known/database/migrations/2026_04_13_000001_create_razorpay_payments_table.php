<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('razorpay_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('type', 30); // membership, order, booking
            $table->unsignedBigInteger('reference_id')->nullable(); // order_id / booking_id / kyc_id
            $table->string('razorpay_payment_id')->nullable();
            $table->string('razorpay_order_id')->nullable();
            $table->string('razorpay_signature')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('INR');
            $table->string('status', 30)->default('Pending'); // Pending, Paid, Failed
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['type', 'reference_id']);
            $table->index('razorpay_order_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('razorpay_payments');
    }
};
