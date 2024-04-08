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
        $payke_resource_id = DB::table('deploy_settings')->where('key', 'payke_resource_id')->get();
        if(count($payke_resource_id) == 0){
            DB::table('deploy_settings')->insert([
                'key' => 'payke_resource_id',
                'value' => '0'
            ]);
        }

        $tag_id = DB::table('deploy_settings')->where('key', 'tag_id')->get();
        if(count($tag_id) == 0){
            DB::table('deploy_settings')->insert([
                'key' => 'tag_id',
                'value' => '0'
            ]);
        }

        $payke_x_auth_token = DB::table('deploy_settings')->where('key', 'payke_x_auth_token')->get();
        if(count($payke_x_auth_token) == 0){
            DB::table('deploy_settings')->insert([
                'key' => 'payke_x_auth_token',
                'value' => 'xyzxyzxyz'
            ]);
        }
    }
}
