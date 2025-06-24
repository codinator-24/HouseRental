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
        Schema::table('floors', function (Blueprint $table) {
            $table->unique('house_id');
            $table->integer('num_floor')->after('house_id')->nullable();
            $table->integer('num_bedroom')->after('num_room')->nullable();
            $table->string('amenity')->after('bathroom') ->nullable();
            $table->string('pet_policy')->after('amenity')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('floors', function (Blueprint $table) {
            $table->dropUnique(['house_id']);
            $table->dropColumn(['num_floor', 'num_bedroom', 'amenity', 'pet_policy']);
        });
    }
};
