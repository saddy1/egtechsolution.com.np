<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('credits', function (Blueprint $table) {
            if (Schema::hasColumn('credits', 'credit_date_ad')) $table->dropColumn('credit_date_ad');
            if (Schema::hasColumn('credits', 'due_date_ad'))    $table->dropColumn('due_date_ad');
        });
    }
    public function down(): void {
        Schema::table('credits', function (Blueprint $table) {
            $table->date('credit_date_ad')->nullable();
            $table->date('due_date_ad')->nullable();
        });
    }
};
