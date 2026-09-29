<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMembershipPaymentScreenshotToSellerKycVerificationsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('seller_kyc_verifications')) {
            return;
        }

        if (!Schema::hasColumn('seller_kyc_verifications', 'membership_payment_screenshot_reference')) {
            Schema::table('seller_kyc_verifications', function (Blueprint $table) {
                $table->string('membership_payment_screenshot_reference', 255)->nullable();
            });
        }
    }

    public function down()
    {
        if (!Schema::hasTable('seller_kyc_verifications')) {
            return;
        }

        if (Schema::hasColumn('seller_kyc_verifications', 'membership_payment_screenshot_reference')) {
            Schema::table('seller_kyc_verifications', function (Blueprint $table) {
                $table->dropColumn('membership_payment_screenshot_reference');
            });
        }
    }
}
