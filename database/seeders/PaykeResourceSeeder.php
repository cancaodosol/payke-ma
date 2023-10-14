<?php

namespace Database\Seeders;

use App\Models\PaykeResource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaykeResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaykeResource::factory()->count(1)->create();
    }
}
