<?php

namespace App\Services;

use App\Services\PaykeDbService;
use App\Models\DeploySetting;
use App\Models\DeploySettingUnit;
use App\Models\PaykeHost;
use App\Models\PaykeResource;
use App\Models\PaykeUser;
use App\Models\PaykeUserTag;

class DeploySettingService
{
    public function find_all()
    {
        return DeploySetting::where("no", "<>", 0)->get();
    }

    public function find_base()
    {
        return DeploySetting::where("no", "=", 0)->get();
    }

    public function find_units_all()
    {
        $settings = $this->find_all();
        $settingsByNo = $settings->groupBy(function ($setting) {
            return $setting->no;
        });

        $units = [];
        foreach ($settingsByNo as $no => $settings) {
            $units[] = $this->create_deploy_setting_unit($no, $settings);
        }

        return $units;
    }

    public function create_deploy_setting_unit($no, $settings){
        $unit = new DeploySettingUnit($no, $settings);

        if($unit->get_value("payke_resource_id")){
            $payke = PaykeResource::where("id", $unit->get_value("payke_resource_id"))->first();
            $unit->set_payke($payke);
        }

        if($unit->get_value("payke_tag_id")){
            $tag = PaykeUserTag::where("id", $unit->get_value("payke_tag_id"))->first();
            $unit->set_tag($tag);
        }

        if($unit->get_value("payke_host_id")){
            $host = PaykeHost::where("id", $unit->get_value("payke_host_id"))->first();
            $unit->set_host($host);
        }

        $dService = new PaykeDbService();
        $dbs = $dService->find_ready_host_dbs($unit->get_value("payke_host_id"));
        $unit->set_ready_dbs_count(count($dbs));

        return $unit;
    }

    public function find_by_key($no, string $key)
    {
        $setting = DeploySetting::where([['no', '=', $no], ['key', '=', $key]])->first();
        if($setting) return $setting;

        $newSetting =  new DeploySetting();
        $newSetting->no = $no;
        $newSetting->key = $key;
        $newSetting->value = null;
        return $newSetting;
    }

    public function get_value($no, string $key)
    {
        $setting = $this->find_by_key($no, $key);
        return $setting->value;
    }

    public function find_by_no(int $no)
    {
        $settings = DeploySetting::where([['no', '=', $no]])->get();
        if(!$settings) return new DeploySettingUnit($no, []);

        $unit = new DeploySettingUnit($no, $settings);

        return $unit;
    }

    public function edit($settings)
    {
        foreach ($settings as $setting) {
            $current = $this->find_by_key($setting->no, $setting->key);
            if($current->id){
                $current->value = $setting->value;
                $current->save();
            } else {
                $setting->save();
            }
        }
    }

    public function next_no()
    {
        $setting = DeploySetting::orderByRaw('no DESC')->first();
        return $setting ? $setting->no + 1 : 1;
    }
}