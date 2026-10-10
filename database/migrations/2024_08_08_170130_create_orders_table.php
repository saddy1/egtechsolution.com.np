<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Customer snapshot
            $table->string('name');
            $table->string('phone', 40)->nullable();     // string (can contain +, spaces)
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('zip', 20)->nullable();       // string, not numeric

            // Money
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);

            // Payment & status
            $table->enum('payment_method', ['cod', 'debit', 'paypal'])->default('cod');
            $table->string('order_status', 30)->default('pending'); // pending|confirmed|shipped|delivered|cancelled

            $table->timestamp('placed_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'order_status']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('orders');
    }
};
