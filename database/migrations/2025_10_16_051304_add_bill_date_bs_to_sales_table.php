<?php

// database/migrations/2025_10_16_000500_add_bill_date_bs_to_sales_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'bill_date_bs')) {
                $table->string('bill_date_bs', 15)->nullable()->after('bill_no');
            }
        });
    }
    public function down(): void {
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'bill_date_bs')) {
                $table->dropColumn('bill_date_bs');
            }
        });
    }
};
