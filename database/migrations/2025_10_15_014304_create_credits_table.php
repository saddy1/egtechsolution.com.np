<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->string('party_name');                 // person/org
            $table->string('contact')->nullable();
            $table->string('address')->nullable();
            $table->string('details')->nullable();        // short description/purpose
            $table->decimal('principal_amount', 12, 2);   // initial credit
            $table->decimal('outstanding_amount', 12, 2); // remaining
            // Nepali dates (BS) stored as string for fidelity; AD for queries
            $table->string('credit_date_bs', 15);
            $table->date('credit_date_ad');
            $table->string('due_date_bs', 15)->nullable();
            $table->date('due_date_ad')->nullable();
            $table->enum('status', ['open','closed'])->default('open');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('credits');
    }
};
