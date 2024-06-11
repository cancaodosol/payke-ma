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
            if($setting->key == "sort_no") $this->settings["sort_no"] = $setting;
            if($setting->key == "is_plan") $this->settings["is_plan"] = $setting;
            if($setting->key == "payke_order_url") $this->settings["payke_order_url"] = $setting;
            if($setting->key == "plan_explain") $this->settings["plan_explain"] = $setting;
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

    public function to_array()
    {
        return [
            "no" => $this->no,
            "setting_title" => $this->get_value("setting_title"),
            "sort_no" => $this->get_value("sort_no"),
            "is_plan" => $this->get_value("is_plan"),
            "payke_order_url" => $this->get_value("payke_order_url"),
            "plan_explain" => $this->get_value("plan_explain"),
            "payke_resource_id" => $this->get_value("payke_resource_id"),
            "payke_tag_id" => $this->get_value("payke_tag_id"),
            "payke_host_id" => $this->get_value("payke_host_id"),
            "payke_enable_affiliate" => $this->get_value("payke_enable_affiliate"),
            "payke_x_auth_token" => $this->get_value("payke_x_auth_token")
        ];
    }
}
