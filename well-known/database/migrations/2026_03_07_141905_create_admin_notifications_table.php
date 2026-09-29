<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type');  // user_registered, order_placed, booking_made, order_shipped, etc.
            $table->string('title');
            $table->text('message');
            $table->string('icon')->nullable();
            $table->string('icon_color')->default('primary');
            $table->string('link')->nullable();
            $table->unsignedBigInteger('related_id')->nullable(); // ID of related entity
            $table->string('related_type')->nullable(); // Model class name
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->index(['is_read', 'created_at']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_notifications');
    }
}
