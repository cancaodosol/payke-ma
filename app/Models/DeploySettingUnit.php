<?php

namespace App\Models;

class DeploySettingUnit
{
    public function __construct($no, $settings)
    {
        $this->no = $no;
        $this->settings = [];
        foreach ($settings as $setting) {
            if($setting->key == "setting_title") $this->settings["setting_title"] = $setting;
            if($setting->key == "payke_resource_id") $this->settings["payke_resource_id"] = $setting;
            if($setting->key == "payke_tag_id") $this->settings["payke_tag_id"] = $setting;
            if($setting->key == "payke_host_id") $this->settings["payke_host_id"] = $setting;
            if($setting->key == "payke_enable_affiliate") $this->settings["payke_enable_affiliate"] = $setting;
            if($setting->key == "payke_x_auth_token") $this->settings["payke_x_auth_token"] = $setting;
        }

        $this->payke = null;
        $this->tag = null;
        $this->host = null;
        $this->ready_dbs_count = null;
    }

    public function get_value($key)
    {
        return array_key_exists($key, $this->settings) ? $this->settings[$key]->value : null;
    }

    public function match_x_auth_token(string $x_auth_token)
    {
        return $x_auth_token == $this->get_value("payke_x_auth_token");
    }

    public function set_payke($payke)
    {
        $this->payke = $payke;
        return $this;
    }

    public function set_tag($tag)
    {
        $this->tag = $tag;
        return $this;
    }

    public function set_host($host)
    {
        $this->host = $host;
        return $this;
    }

    public function set_ready_dbs_count($ready_dbs_count)
    {
        $this->ready_dbs_count = $ready_dbs_count;
        return $this;
    }
}
