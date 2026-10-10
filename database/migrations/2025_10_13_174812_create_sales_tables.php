
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sales', function (Blueprint $t) {
            $t->id();
            $t->string('bill_no')->unique();                // auto-generated
            $t->string('billing_name');                     // customer/buyer name
            $t->string('pan')->nullable();                  // nullable
            $t->string('address')->nullable();
            $t->text('remarks')->nullable();
            $t->decimal('subtotal', 12, 2)->default(0);     // sum of items (selling)
            $t->decimal('total_cost', 12, 2)->default(0);   // sum of items (cost)
            $t->decimal('profit', 12, 2)->default(0);       // subtotal - total_cost
            $t->timestamps();
        });

        Schema::create('sale_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $t->foreignId('product_id')->constrained('products')->restrictOnDelete();

            $t->string('product_name');                     // snapshot
            $t->integer('quantity');
            $t->decimal('selling_price', 12, 2);            // at time of sale
            $t->decimal('cost_price', 12, 2)->default(0);   // snapshot from product

            $t->decimal('line_total', 12, 2);               // quantity * selling_price
            $t->decimal('line_cost_total', 12, 2);          // quantity * cost_price
            $t->decimal('line_profit', 12, 2);              // line_total - line_cost_total
            $t->text('item_remarks')->nullable();

            $t->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('sales');
    }
};
