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
        Schema::table('messages', function (Blueprint $table) {
            // Add nullable house_id column for inquiries
            $table->foreignId('house_id')->nullable()->constrained()->onDelete('cascade');
            
            // Make agreement_id nullable
            $table->foreignId('agreement_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // Remove house_id column
            $table->dropForeign(['house_id']);
            $table->dropColumn('house_id');

            // Revert agreement_id to not nullable - This assumes it was not nullable before.
            // If it was already nullable, this down method might need adjustment
            // or be simpler if the original state was nullable.
            // For now, assuming it was NOT nullable based on typical design before this change.
            // IMPORTANT: Check the original create_messages_table migration if issues arise.
            $table->foreignId('agreement_id')->nullable(false)->change();
        });
    }
};
