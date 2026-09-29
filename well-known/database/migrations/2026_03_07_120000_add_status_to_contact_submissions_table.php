<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToContactSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('contact_submissions')) {
            return;
        }

        Schema::table('contact_submissions', function (Blueprint $table) {
            if (!Schema::hasColumn('contact_submissions', 'status')) {
                $table->string('status', 20)->default('Pending')->after('country_type');
            }

            if (!Schema::hasColumn('contact_submissions', 'resolved_at')) {
                $table->timestamp('resolved_at')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('contact_submissions')) {
            return;
        }

        Schema::table('contact_submissions', function (Blueprint $table) {
            if (Schema::hasColumn('contact_submissions', 'resolved_at')) {
                $table->dropColumn('resolved_at');
            }

            if (Schema::hasColumn('contact_submissions', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
}
