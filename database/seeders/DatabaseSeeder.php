<?php

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\CurrencyRateSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a specific tenant
        User::factory()->create([
            'fullName' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create 10 random Users
        User::factory(10)->create();

        $this->call(CurrencyRateSeeder::class);
    }
}
