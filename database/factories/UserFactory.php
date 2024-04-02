<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => "松井英之",
            'role' => User::ROLE__ADMIN,
            'email' => "canac0.d0.s0lh1de.m24w@gmail.com",
            'email_verified_at' => now(),
            'password' => '$2y$10$AtCZrDj1p4V0weeDX6TpCOrhjhr3cp2aKQUKzBkvIa6z/i7.uZFfa', // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
