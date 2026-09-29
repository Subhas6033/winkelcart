<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->nullable();
            $table->string('image')->nullable();
            $table->json('gallery')->nullable();
            $table->text('desc')->nullable();
            $table->string('checkin_time')->nullable();
            $table->string('checkout_time')->nullable();
            $table->string('rating')->nullable();
            $table->string('contact')->nullable();
            $table->json('amenities')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hotels');
    }
};
