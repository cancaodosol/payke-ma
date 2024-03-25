<?php

namespace Database\Seeders;

use App\Models\DeploySetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeploySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('deploy_settings')->insert([
            'key' => 'payke_resource_id',
            'value' => '0'
        ]);
        DB::table('deploy_settings')->insert([
            'key' => 'tag_id',
            'value' => '0'
        ]);
    }
}
