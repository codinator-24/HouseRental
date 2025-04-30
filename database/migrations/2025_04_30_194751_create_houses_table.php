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
        Schema::create('houses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('landlord_id')->constrained('users')->onDelete('cascade'); // Foreign key to users table
            $table->string('title');
            $table->longText('description');
            $table->string('first_address');
            $table->string('second_address')->nullable(); // Made nullable in case it's optional
            $table->string('city');
            $table->string('location_url')->nullable(); // Made nullable in case it's optional
            $table->string('property_type');
            $table->integer('num_room');
            $table->integer('num_floor')->default(1); // Added a default value
            $table->double('square_footage');
            $table->double('rent_amount');
            $table->string('status')->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('houses');
    }
};
