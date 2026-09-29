<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderStatusTimelinesTable extends Migration
{
    public function up()
    {
        Schema::create('order_status_timelines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->index();
            $table->string('status', 30)->index();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('changed_by')->nullable()->index();
            $table->string('changed_by_role', 60)->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('changed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_status_timelines');
    }
}
