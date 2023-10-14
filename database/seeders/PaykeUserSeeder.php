<?php

namespace Database\Seeders;

use App\Models\PaykeUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaykeUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaykeUser::factory()->count(1)->create();
    }
}
