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
        Schema::table('agreements', function (Blueprint $table) {
            $table->boolean('landlord_keys_delivered')->default(false)->after('status');
            $table->date('key_delivery_deadline')->nullable()->after('landlord_keys_delivered');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agreements', function (Blueprint $table) {
            $table->dropColumn(['landlord_keys_delivered', 'key_delivery_deadline']);
        });
    }
};
