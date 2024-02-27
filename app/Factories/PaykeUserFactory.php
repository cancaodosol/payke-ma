<?php

namespace App\Factories;

use App\Services\PaykeUserService;
use App\Services\PaykeDbService;
use App\Services\PaykeResourceService;
use App\Models\PaykeUser;

class PaykeUserFactory
{
    public function create_new_user(string $user_name, string $email_address): PaykeUser
    {
        // PaykeECで使用するデータベースをさがす。
        $dbService = new PaykeDbService();
        $host_dbs = $dbService->find_ready_host_dbs();
        if(count($host_dbs) == 0)
        {
            //TODO この時は、どうしよう?
        }

        $host_id = $host_dbs[0]["host_id"];
        $db_id = $host_dbs[0]["db_id"];

        // 公開アプリ名をランダムで作成する。
        $app_name = $this->create_randam_app_name($host_id);

        // リリースするPaykeECバージョンを取得する。
        $rService = new PaykeResourceService();
        $payke = $rService->get_release_version();

        // ここまで取得したデータを、Paykeユーザーにまとめていく。
        $user = new PaykeUser();

        $user->user_name = $user_name;
        $user->email_address = $email_address;

        $user->enable_affiliate = false;
        $user->memo = "";

        $user->payke_host_id = $host_id;
        $user->payke_db_id = $db_id;
        $user->payke_resource_id = $payke->id;
        $user->user_app_name = $app_name;

        $user->set_user_folder_id($host_id, $db_id);
        $user->set_app_url($user->PaykeHost->hostname, $user->user_app_name);

        return $user;
    }

    private function create_randam_app_name($host_id)
    {
        $app_name = $this->create_randam();
        $service = new PaykeUserService();
        if(!$service->exists_same_name($host_id, $app_name)) return $app_name;
        return create_app_name($host_id);
    }

    private function create_randam($length = 8)
    {
        return substr(base_convert(md5(uniqid()), 16, 36), 0, $length);
    }
}