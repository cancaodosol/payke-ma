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
        $setting = $this->find_by_key($key);
        return $setting ? $setting->value : false;
    }

    public function match_x_auth_token(string $x_auth_token)
    {
        return $x_auth_token == $this->get_value("payke_x_auth_token");
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