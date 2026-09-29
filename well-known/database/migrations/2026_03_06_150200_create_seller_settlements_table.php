<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerSettlementsTable extends Migration
{
    public function up()
    {
        Schema::create('seller_settlements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('gross_amount', 12, 2);
            $table->decimal('commission_amount', 12, 2)->default(0);
            $table->decimal('net_amount', 12, 2);
            $table->string('status', 30)->default('Pending')->index();
            $table->timestamp('paid_at')->nullable();
            $table->string('reference_no', 120)->nullable();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('processed_by')->nullable()->index();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('processed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('seller_settlements');
    }
}
