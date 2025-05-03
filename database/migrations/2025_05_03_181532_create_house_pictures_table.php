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
        Schema::create('house_pictures', function (Blueprint $table) {
            $table->id(); // Id (PK), auto-incrementing big integer
            $table->foreignId('house_id') // Foreign key column
                  ->constrained('houses') // References 'id' on 'houses' table
                  ->onDelete('cascade'); // If a house is deleted, delete its pictures too
            $table->string('image_url'); // Image URL column
            $table->text('caption')->nullable(); // Optional caption column
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('house_pictures');
    }
};