<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnRefundRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('return_refund_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('order_id')->nullable()->index();
            $table->string('order_number', 64)->nullable()->index();
            $table->string('request_type', 20)->default('Return')->index();
            $table->text('reason');
            $table->string('status', 30)->default('Pending')->index();
            $table->decimal('refund_amount', 12, 2)->nullable();
            $table->text('admin_note')->nullable();
            $table->unsignedBigInteger('handled_by')->nullable()->index();
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            $table->foreign('handled_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('return_refund_requests');
    }
}
