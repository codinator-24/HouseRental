<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant>
 */
class TenantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fullName'    => $this->faker->name(),
            'email'       => $this->faker->unique()->safeEmail(),
            'password'    => Hash::make('password'), // or bcrypt() if you prefer
            'address'     => $this->faker->address(),
            'contactNo'   => $this->faker->phoneNumber(),
            'userTitle'   => $this->faker->title(),
            'remember_token' => Str::random(10),
        ];
    }
}
