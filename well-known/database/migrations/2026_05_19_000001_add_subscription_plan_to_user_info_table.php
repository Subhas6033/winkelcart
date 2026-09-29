<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_info', function (Blueprint $table) {
            if (!Schema::hasColumn('user_info', 'subscription_plan')) {
                $table->enum('subscription_plan', ['free', 'paid'])->default('free')->after('country_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_info', function (Blueprint $table) {
            if (Schema::hasColumn('user_info', 'subscription_plan')) {
                $table->dropColumn('subscription_plan');
            }
        });
    }
};
