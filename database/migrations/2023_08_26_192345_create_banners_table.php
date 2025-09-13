// database/migrations/2025_01_01_000000_create_banners_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('name');                 // internal/admin name
            $table->string('image_path');           // storage path
            $table->string('alt_text')->nullable(); // img alt text (SEO)
            $table->string('link_url')->nullable(); // optional CTA link
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
