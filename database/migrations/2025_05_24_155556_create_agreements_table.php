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
        Schema::create('agreements', function (Blueprint $table) {
            $table->id(); // Id (PK) - auto-incrementing primary key
            $table->foreignId('booking_id')->constrained()->onDelete('cascade'); // booking_id (FK to bookings table)
            $table->timestamp('signed_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->decimal('rent_amount', 10, 2); // Assuming 10 total digits, 2 decimal places
            $table->string('rent_frequency'); // e.g., 'monthly', 'annually'
            $table->string('status')->default('pending'); // e.g., 'pending', 'active', 'expired', 'terminated'
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agreements');
    }
};
