<?php

namespace App\Factories;

use Illuminate\Support\Str;

use App\Services\PaykeUserService;
use App\Services\PaykeDbService;
use App\Services\PaykeResourceService;
use App\Services\DeploySettingService;
use App\Models\PaykeUser;
use App\Models\DeploySettingUnit;
use App\Helpers\SecurityHelper;

class PaykeUserFactory
{
    public function create_new_user(string $user_name, string $email_address, DeploySettingUnit $settingUnit): PaykeUser
    {
        // Paykeで使用するデータベースをさがす。
        $dbService = new PaykeDbService();
        $host_dbs = $dbService->find_ready_host_dbs($settingUnit->get_value("payke_host_id"));

        $host_id = count($host_dbs) == 0 ? null : $host_dbs[0]["host_id"];
        $db_id =  count($host_dbs) == 0 ? null : $host_dbs[0]["db_id"];

        // 公開アプリ名をランダムで作成する。
        $app_name = $this->create_randam_app_name($host_id);

        // ここまで取得したデータを、Paykeユーザーにまとめていく。
        $user = new PaykeUser();

        $user->uuid = (string)Str::uuid();

        $user->user_name = $user_name;
        $user->email_address = $email_address;

        $user->enable_affiliate = $settingUnit->get_value("payke_enable_affiliate");
        $user->memo = "";

        $user->tag_id = $settingUnit->get_value("payke_tag_id");
        $user->payke_host_id = $host_id;
        $user->payke_db_id = $db_id;
        $user->payke_resource_id = $settingUnit->get_value("payke_resource_id");
        $user->deploy_setting_no = $settingUnit->no;
        $user->user_app_name = $app_name;

        if($host_id != null)
        {
            $user->set_user_folder_id($host_id, $db_id);
            $user->set_app_url($user->PaykeHost->hostname, $user->user_app_name);
        } else {
            $user->user_folder_id = "";
            $user->app_url = "";
        }

        return $user;
    }

    private function create_randam_app_name($host_id)
    {
        $app_name = SecurityHelper::create_ramdam_string();
        if($host_id == null) return $app_name;
        $service = new PaykeUserService();
        if(!$service->exists_same_name($host_id, $app_name)) return $app_name;
        return create_app_name($host_id);
    }
}