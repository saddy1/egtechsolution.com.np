<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('is_admin')->default(value(1));
            $table->rememberToken();
            $table->timestamps();
        });

        // Insert admin data
        DB::table('admins')->insert([
            'name' => 'Sadanand Paneru',
            'contact' => '9843521965',
            'email' => 'sadanand@ioepc.edu.np',
            'email_verified_at' => now(),
            'password' => Hash::make('S@ddy9843521965'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('admins')->insert([
            'name' => 'Laxman Paneru',
            'contact' => '9864830276',
            'email' => 'egtechsolution1@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('egtech@12345'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
