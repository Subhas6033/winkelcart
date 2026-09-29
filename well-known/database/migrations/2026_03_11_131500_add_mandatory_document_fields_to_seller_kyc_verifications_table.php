<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMandatoryDocumentFieldsToSellerKycVerificationsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('seller_kyc_verifications')) {
            return;
        }

        if (!Schema::hasColumn('seller_kyc_verifications', 'bank_passbook_reference')) {
            Schema::table('seller_kyc_verifications', function (Blueprint $table) {
                $table->string('bank_passbook_reference', 255)->nullable();
            });
        }

        if (!Schema::hasColumn('seller_kyc_verifications', 'id_card_reference')) {
            Schema::table('seller_kyc_verifications', function (Blueprint $table) {
                $table->string('id_card_reference', 255)->nullable();
            });
        }

        if (!Schema::hasColumn('seller_kyc_verifications', 'pan_card_reference')) {
            Schema::table('seller_kyc_verifications', function (Blueprint $table) {
                $table->string('pan_card_reference', 255)->nullable();
            });
        }

        if (!Schema::hasColumn('seller_kyc_verifications', 'gst_certificate_reference')) {
            Schema::table('seller_kyc_verifications', function (Blueprint $table) {
                $table->string('gst_certificate_reference', 255)->nullable();
            });
        }
    }

    public function down()
    {
        if (!Schema::hasTable('seller_kyc_verifications')) {
            return;
        }

        foreach ([
            'bank_passbook_reference',
            'id_card_reference',
            'pan_card_reference',
            'gst_certificate_reference',
        ] as $column) {
            if (Schema::hasColumn('seller_kyc_verifications', $column)) {
                Schema::table('seller_kyc_verifications', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
}
