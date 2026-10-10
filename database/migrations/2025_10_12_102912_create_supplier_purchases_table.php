<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('supplier_purchases', function (Blueprint $t) {
            $t->id();
            $t->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete(); // links to suppliers.id
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();           // links to products.id

            $t->integer('quantity');

            // rates
            $t->decimal('rate_excl_vat', 12, 2);   // rate without VAT
            $t->decimal('rate_incl_vat', 12, 2);   // rate with 13% VAT
            $t->decimal('total', 12, 2);           // quantity * rate_incl_vat

            $t->string('bill_no', 100)->nullable();
            $t->string('purchased_at')->nullable();

            // snapshot of supplier/product names (handy for reports even if names change later)
            $t->string('supplier_name')->nullable();
            $t->string('product_name');

            $t->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('supplier_purchases');
    }
};
