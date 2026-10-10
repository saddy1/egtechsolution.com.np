<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // 'web' = placed on this site, 'broadtech' = sent by broadtechinfosys.com.np
            $table->string('source', 30)->default('web');
            $table->string('external_reference', 60)->nullable()->unique();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['external_reference']);
            $table->dropColumn(['source', 'external_reference']);
        });
    }
};
