<?php

namespace App\Services;

use App\Models\DeploySetting;
use App\Models\PaykeResource;
use App\Models\PaykeUser;

class DeploySettingService
{
    public function find_all()
    {
        return DeploySetting::all();
    }

    public function find_by_key(string $key)
    {
        return DeploySetting::where('key', $key)->firstOrFail();
    }

    public function get_value(string $key)
    {
        $setting = DeploySetting::where('key', $key)->firstOrFail();
        return $setting ? $setting->value : false;
    }

    public function edit($settings)
    {
        foreach ($settings as $setting) {
            $current = $this->find_by_key($setting->key);
            $current->value = $setting->value;
            $current->save();
        }
    }
}