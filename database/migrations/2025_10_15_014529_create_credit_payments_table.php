<?php
// database/migrations/2025_01_01_000001_create_credit_payments_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('credit_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('paid_date_bs', 15);
            $table->date('paid_date_ad');
            $table->string('reference')->nullable(); // receipt no / note
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('credit_payments');
    }
};
