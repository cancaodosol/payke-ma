<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaykeResource>
 */
class PaykeResourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'version' => 'v3.22.3',
            'payke_name' => 'payke-ec_v3-22-3',
            'payke_zip_name' => 'payke-ec-cae6ae8bf6d3',
            'payke_zip_file_path' => '/payke_resources/zips/payke-ec-cae6ae8bf6d3.zip'
        ];
    }
}
