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
        // Rename the table
        Schema::rename('tenants', 'users');

        // IMPORTANT: If other tables have foreign keys referencing 'tenants',
        // you'll need to drop those constraints here, rename the table,
        // and then re-add the constraints referencing the new 'users' table.
        // Example (adjust table/column names as needed):
        // Schema::table('some_other_table', function (Blueprint $table) {
        //     $table->dropForeign(['tenant_id']); // Drop constraint referencing old table
        // });
        //
        // Schema::rename('tenants', 'users'); // Rename the main table
        //
        // Schema::table('some_other_table', function (Blueprint $table) {
        //     // Optionally rename the foreign key column if desired
        //     // $table->renameColumn('tenant_id', 'user_id');
        //     // Re-add the foreign key constraint referencing the new table
        //     $table->foreign('user_id' /* or 'tenant_id' if not renamed */)
        //           ->references('id')
        //           ->on('users')
        //           ->onDelete('cascade'); // or your desired action
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the renaming process
        // Similar caution applies here for foreign keys if you handled them in 'up'
        Schema::rename('users', 'tenants');

        // Example reverse for foreign keys:
        // Schema::table('some_other_table', function (Blueprint $table) {
        //     $table->dropForeign(['user_id']); // Drop constraint referencing 'users'
        // });
        //
        // Schema::rename('users', 'tenants');
        //
        // Schema::table('some_other_table', function (Blueprint $table) {
        //     // $table->renameColumn('user_id', 'tenant_id'); // If renamed in 'up'
        //     $table->foreign('tenant_id')
        //           ->references('id')
        //           ->on('tenants')
        //           ->onDelete('cascade');
        // });
    }
};