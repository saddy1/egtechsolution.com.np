<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Site settings editable from admin (SMTP, WhatsApp). Secrets are stored encrypted.
        Schema::create('settings', function (Blueprint $table) {
            $table->string('key', 100)->primary();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // 6-digit password reset codes for customers (guard "web") and admins (guard "admin")
        Schema::create('password_reset_codes', function (Blueprint $table) {
            $table->id();
            $table->string('guard', 20);
            $table->string('email');
            $table->string('code_hash');
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->index(['guard', 'email']);
        });

        // Admin passkeys (WebAuthn): fingerprint / Face ID / phone screen lock
        Schema::create('admin_passkeys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('credential_id', 512)->unique(); // base64url
            $table->text('public_key');                     // PEM
            $table->unsignedBigInteger('sign_count')->default(0);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_passkeys');
        Schema::dropIfExists('password_reset_codes');
        Schema::dropIfExists('settings');
    }
};
