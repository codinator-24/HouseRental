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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User who submitted the report
            $table->foreignId('house_id')->constrained()->onDelete('cascade'); // House being reported
            $table->foreignId('reported_user_id')->constrained('users')->onDelete('cascade'); // Landlord of the house
            $table->string('reason_category');
            $table->text('description');
            $table->string('status')->default('pending'); // e.g., pending, under_review, resolved, dismissed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
