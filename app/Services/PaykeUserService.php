<?php

namespace App\Services;

use App\Models\PaykeHost;
use App\Models\PaykeResource;
use App\Models\PaykeDb;
use App\Models\PaykeUser;

class PaykeUserService
{
    public function save_init(PaykeUser $user): void
    {
        $user->status = PaykeUser::STATUS__BEFORE_SETTING;
        $user->PaykeHost->status = PaykeHost::STATUS__IN_USE;
        $user->PaykeDb->status = PaykeDb::STATUS__IN_USE;
        $url = "https://{$user->PaykeHost->hostname}/{$user->user_app_name}/admin/users";
        $user->app_url = $url;
        $user->save();
        $user->PaykeHost->save();
        $user->PaykeDb->save();
    }

    public function open_affiliate(PaykeUser $user): void
    {
        $dService = new DeployService();
        $log = [];
        $is_success = $dService->open_affiliate($user->PaykeHost, $user, $log);
        if($is_success)
        {
            $user->enable_affiliate = 1; // true: 1
            $user->save();
        }
        else
        {
            $this->save_has_error($user, "アフィリエイト機能の有効化に失敗しました。");
        }
    }

    public function close_affiliate(PaykeUser $user): void
    {
        $dService = new DeployService();
        $log = [];
        $is_success = $dService->close_affiliate($user->PaykeHost, $user, $log);
        if($is_success)
        {
            $user->enable_affiliate = 0; // false: 1
            $user->save();
        }
        else
        {
            $this->save_has_error($user, "アフィリエイト機能の無効化に失敗しました。");
        }
    }

    public function save_active(PaykeUser $user): void
    {
        $user->status = PaykeUser::STATUS__ACTIVE;
        $user->save();
    }

    public function save_has_error(PaykeUser $user, string $error): void
    {
        $user->status = PaykeUser::STATUS__HAS_ERROR;
        $user->save();
    }

    public function find_by_id(int $id)
    {
        return PaykeUser::where('id', $id)->firstOrFail();
    }
}