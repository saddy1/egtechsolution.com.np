<?php

// database/migrations/2025_10_15_000101_drop_ad_dates_from_credit_payments.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('credit_payments', function (Blueprint $table) {
            if (Schema::hasColumn('credit_payments', 'paid_date_ad')) $table->dropColumn('paid_date_ad');
        });
    }
    public function down(): void {
        Schema::table('credit_payments', function (Blueprint $table) {
            $table->date('paid_date_ad')->nullable();
        });
    }
};

