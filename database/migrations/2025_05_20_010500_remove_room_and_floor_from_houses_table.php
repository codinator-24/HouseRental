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
        Schema::table('houses', function (Blueprint $table) {
            $table->dropColumn('num_room');
            $table->dropColumn('num_floor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('houses', function (Blueprint $table) {
            // If you want to be able to roll back, define how to re-add the columns.
            // Adjust the type and placement as per your original schema.
            $table->integer('num_room')->after('property_type'); // Or wherever it was
            $table->integer('num_floor')->after('num_room');    // Or wherever it was
        });
    }
};