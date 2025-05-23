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
        Schema::table('users', function (Blueprint $table) {
            // Change the 'status' column to have a default value
            // Assuming 'status' is a string column and was not nullable.
            // If it had other properties (e.g., specific length, nullable),
            // you should re-specify them here.
            $table->string('status')->default('Not Verified')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert the 'status' column to its previous state.
            // This assumes the column was a non-nullable string without a specific default.
            // If it was nullable, you'd add ->nullable() here.
            // If it had a different default, you'd set it back.
            // Setting default(null) or simply re-declaring without default usually removes it.
            $table->string('status')->default(null)->change(); // Or simply $table->string('status')->change();
                                                          // if the original had no default and was NOT NULL.
                                                          // Using default(null) is safer if it might have been nullable
                                                          // or to explicitly remove the default.
                                                          // If it was strictly NOT NULL and had no default,
                                                          // $table->string('status')->change(); would be the most direct revert.
                                                          // For this example, let's assume we want to remove the default
                                                          // and if it was NOT NULL, it remains NOT NULL.
                                                          // $table->string('status')->change(); is often sufficient to remove the default.
        });
    }
};
