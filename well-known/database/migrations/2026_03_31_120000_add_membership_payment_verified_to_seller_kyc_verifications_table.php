<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMembershipPaymentVerifiedToSellerKycVerificationsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('seller_kyc_verifications')) {
            return;
        }

        if (!Schema::hasColumn('seller_kyc_verifications', 'membership_payment_verified')) {
            Schema::table('seller_kyc_verifications', function (Blueprint $table) {
                $table->boolean('membership_payment_verified')->default(false);
            });
        }
    }

    public function down()
    {
        if (!Schema::hasTable('seller_kyc_verifications')) {
            return;
        }

        if (Schema::hasColumn('seller_kyc_verifications', 'membership_payment_verified')) {
            Schema::table('seller_kyc_verifications', function (Blueprint $table) {
                $table->dropColumn('membership_payment_verified');
            });
        }
    }
}
