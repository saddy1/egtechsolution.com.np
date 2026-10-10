<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // A group ties together products that are the same model in different
        // options (RAM, storage, colour...). Each option stays a full product
        // with its own price, stock and SKU, so orders, billing and stock work as before.
        Schema::create('variant_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('variant_group_id')->nullable()->constrained()->nullOnDelete();
            // e.g. {"RAM": "12GB", "Storage": "512GB SSD", "Colour": "Silver"}
            $table->json('variant_options')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('variant_group_id');
            $table->dropColumn('variant_options');
        });

        Schema::dropIfExists('variant_groups');
    }
};
