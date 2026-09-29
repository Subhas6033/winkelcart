<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerKycVerificationsTable extends Migration
{
    public function up()
    {
        Schema::create('seller_kyc_verifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('legal_name', 190)->nullable();
            $table->string('id_type', 60)->nullable();
            $table->string('id_number', 120)->nullable();
            $table->string('document_reference', 255)->nullable();
            $table->string('status', 30)->default('Pending')->index();
            $table->text('admin_note')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable()->index();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('seller_kyc_verifications');
    }
}
