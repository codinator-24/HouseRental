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
                $table->dropColumn('fullName');
                $table->dropColumn('contactNo');
                $table->dropColumn('userTitle');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('full_name')->after('id'); // Or place it where it was originally
            // Add the columns back in a logical order (adjust 'after' as needed)
            $table->string('fullName', 120)->after('id'); // Assuming it was after id
            $table->char('contactNo', 11)->after('password'); // Adjust position based on original schema
            $table->string('userTitle', 100)->after('address'); 
        });
    }
};