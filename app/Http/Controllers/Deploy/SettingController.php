<?php

namespace App\Http\Controllers\Deploy;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeploySetting\EditRequest;
use App\Services\DeploySettingService;
use App\Services\PaykeUserService;
use App\Services\PaykeResourceService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function view_edit()
    {
        $service = new DeploySettingService();
        $payke_resource_id = $service->get_value("payke_resource_id");
        $payke_tag_id = $service->get_value("payke_tag_id");
        $payke_x_auth_token = $service->get_value("payke_x_auth_token");

        $rService = new PaykeResourceService();
        $resources = $rService->find_all_to_array();

        $uService = new PaykeUserService();
        $tags = $uService->get_tags_array();
        array_unshift($tags, ["id" => null, "name" => "-"]);

        $settings = [
            "payke_resource_id" => $payke_resource_id,
            "payke_tag_id" => $payke_tag_id,
            "payke_x_auth_token" => $payke_x_auth_token
        ];

        return view('deploy_setting.edit', ['settings' => $settings, 'resources' => $resources, 'tags' => $tags]);
    }

    public function post_edit(EditRequest $request)
    {
        $service = new DeploySettingService();

        $service->edit($request->to_setting_models());

        session()->flash('successTitle', '自動デプロイ設定を更新しました。');
        return redirect()->route('deploy_setting.edit');
    }
}
