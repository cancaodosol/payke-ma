<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaykeDb>
 */
class PaykeDbFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => 1,
            'payke_host_id' => 1,
            'db_host' => 'localhost',
            'db_username' => 'hirotae_h1de',
            'db_password' => 'matsui1234',
            'db_database' => 'hirotae_payma04'
        ];
    }
}
