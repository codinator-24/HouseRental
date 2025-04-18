<?php

use App\Models\Tenant;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a specific tenant
        Tenant::factory()->create([
            'fullName' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create 10 random tenants
        Tenant::factory(10)->create();
    }
}
