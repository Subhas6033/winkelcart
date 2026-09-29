<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtendedFieldsToSellerKycVerificationsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('seller_kyc_verifications')) {
            return;
        }

        if (!Schema::hasColumn('seller_kyc_verifications', 'pan_number')) {
            Schema::table('seller_kyc_verifications', function (Blueprint $table) {
                $table->string('pan_number', 30)->nullable();
            });
        }

        if (!Schema::hasColumn('seller_kyc_verifications', 'aadhaar_number')) {
            Schema::table('seller_kyc_verifications', function (Blueprint $table) {
                $table->string('aadhaar_number', 20)->nullable();
            });
        }

        if (!Schema::hasColumn('seller_kyc_verifications', 'bank_account_holder')) {
            Schema::table('seller_kyc_verifications', function (Blueprint $table) {
                $table->string('bank_account_holder', 190)->nullable();
            });
        }

        if (!Schema::hasColumn('seller_kyc_verifications', 'bank_account_number')) {
            Schema::table('seller_kyc_verifications', function (Blueprint $table) {
                $table->string('bank_account_number', 50)->nullable();
            });
        }

        if (!Schema::hasColumn('seller_kyc_verifications', 'bank_ifsc_code')) {
            Schema::table('seller_kyc_verifications', function (Blueprint $table) {
                $table->string('bank_ifsc_code', 20)->nullable();
            });
        }

        if (!Schema::hasColumn('seller_kyc_verifications', 'bank_name')) {
            Schema::table('seller_kyc_verifications', function (Blueprint $table) {
                $table->string('bank_name', 190)->nullable();
            });
        }

        if (!Schema::hasColumn('seller_kyc_verifications', 'gst_number')) {
            Schema::table('seller_kyc_verifications', function (Blueprint $table) {
                $table->string('gst_number', 20)->nullable();
            });
        }

        if (!Schema::hasColumn('seller_kyc_verifications', 'address_proof_reference')) {
            Schema::table('seller_kyc_verifications', function (Blueprint $table) {
                $table->string('address_proof_reference', 255)->nullable();
            });
        }

        if (!Schema::hasColumn('seller_kyc_verifications', 'selfie_with_id_reference')) {
            Schema::table('seller_kyc_verifications', function (Blueprint $table) {
                $table->string('selfie_with_id_reference', 255)->nullable();
            });
        }

        if (!Schema::hasColumn('seller_kyc_verifications', 'mobile_verified_at')) {
            Schema::table('seller_kyc_verifications', function (Blueprint $table) {
                $table->timestamp('mobile_verified_at')->nullable();
            });
        }

        if (!Schema::hasColumn('seller_kyc_verifications', 'email_verified_at')) {
            Schema::table('seller_kyc_verifications', function (Blueprint $table) {
                $table->timestamp('email_verified_at')->nullable();
            });
        }
    }

    public function down()
    {
        if (!Schema::hasTable('seller_kyc_verifications')) {
            return;
        }

        foreach ([
            'pan_number',
            'aadhaar_number',
            'bank_account_holder',
            'bank_account_number',
            'bank_ifsc_code',
            'bank_name',
            'gst_number',
            'address_proof_reference',
            'selfie_with_id_reference',
            'mobile_verified_at',
            'email_verified_at',
        ] as $column) {
            if (Schema::hasColumn('seller_kyc_verifications', $column)) {
                Schema::table('seller_kyc_verifications', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
}
