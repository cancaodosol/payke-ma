<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaykeUser>
 */
class PaykeUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => 0,
            'payke_host_id' => 1,
            'payke_db_id' => 1,
            'payke_resource_id' => 1,
            'user_folder_id' => 'user_007131',
            'user_app_name' => 'tarotaro7',
        ];
    }
}
