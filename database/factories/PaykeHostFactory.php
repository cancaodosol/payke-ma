<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaykeHost>
 */
class PaykeHostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => '1',
            'name' => 'エックスサーバー１',
            'hostname' => 'hiderin.xyz',
            'remote_user' => 'hirotae',
            'port' => 10022,
            'identity_file' => './.ssh/hideringa_xserver_rsa',
            'resource_dir' => '~/hiderin.xyz/payke_resources',
            'public_html_dir' => '~/hiderin.xyz/public_html'
        ];
    }
}
