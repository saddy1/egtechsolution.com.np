<?php

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            
            // Pricing
            $table->decimal('price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
            
            // Stock management
            $table->integer('quantity')->default(0);
            $table->enum('stock_status', ['in_stock', 'out_of_stock'])->default('in_stock');
            
            // Relations
            $table->foreignIdFor(Category::class)->constrained();
            $table->foreignIdFor(Brand::class)->constrained();
            
            // Product identification
            $table->string('sku')->unique();
            $table->string('barcode')->nullable();
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            
            // Product management
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};