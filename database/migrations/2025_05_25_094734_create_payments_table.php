<?php

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
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); // Id (PK) - bigIncrements, primary
            $table->foreignId('agreement_id')->constrained('agreements')->onDelete('cascade'); // Assumes you have an 'agreements' table
            $table->decimal('amount', 10, 2); // Amount, e.g., 12345678.90
            $table->string('payment_method'); // e.g., 'cash', 'credit_card', 'bank_transfer'
            $table->string('status')->default('pending'); // e.g., 'pending', 'completed', 'failed', 'refunded'
            $table->timestamp('paid_at')->nullable(); // Timestamp when the payment was made
            $table->text('notes')->nullable(); // Any additional notes
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};