<?php

namespace Database\Seeders;

use App\Models\PaykeHost;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaykeHostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaykeHost::factory()->count(1)->create();
    }
}
