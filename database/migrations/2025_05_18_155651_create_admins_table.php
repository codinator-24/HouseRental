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
        Schema::create('admins', function (Blueprint $table) {
            $table->id(); // Id (PK) - auto-incrementing primary key
            $table->string('full_name');
            $table->string('user_name')->unique(); // Assuming username should be unique
            $table->string('phoneNumber');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('picture')->nullable(); // Path to the picture, nullable if not always present
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};