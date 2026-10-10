<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('products', function (Blueprint $t) {
            // core inventory & pricing (keep names simple)
            $t->decimal('cost_price', 12, 2)->nullable();
            $t->string('supplier_id')->nullable();
        });
    }

    public function down(): void {
        Schema::table('products', function (Blueprint $t) {
            $t->dropColumn(['cost_price','supplier_id']);
        });
    }
};