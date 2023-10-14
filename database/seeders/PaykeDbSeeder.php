<?php

namespace Database\Seeders;

use App\Models\PaykeDb;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaykeDbSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaykeDb::factory()->count(1)->create();
    }
}
