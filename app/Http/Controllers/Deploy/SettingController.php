<?php

namespace App\Http\Controllers\Deploy;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeploySetting\EditRequest;
use App\Http\Requests\DeploySetting\EditBaseRequest;
use App\Services\DeploySettingService;
use App\Services\PaykeUserService;
use App\Services\PaykeHostService;
use App\Services\PaykeResourceService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function view_all(Request $request)
    {
        $service = new DeploySettingService();
        $units = $service->find_units_all();
        return view('deploy_setting.index', ['units' => $units]);
    }

    public function view_create()
    {
        $service = new DeploySettingService();
        $settings = [
            "no" => $service->next_no(),
            "setting_title" => "",
            "payke_resource_id" => null,
            "payke_tag_id" => null,
            "payke_host_id" => null,
            "payke_enable_affiliate" => false,
            "payke_x_auth_token" => ""
        ];

        $hService = new PaykeHostService();
        $hosts = $hService->find_all_to_array();
        array_unshift($hosts, ["id" => null, "name" => "どこでもOK"]);

        $rService = new PaykeResourceService();
        $resources = $rService->find_all_to_array();

        $uService = new PaykeUserService();
        $tags = $uService->get_tags_array();
        array_unshift($tags, ["id" => null, "name" => "なし"]);

        return view('deploy_setting.edit', ['settings' => $settings, 'hosts' => $hosts, 'resources' => $resources, 'tags' => $tags]);
    }

    public function view_edit($no)
    {
        $service = new DeploySettingService();
        $unit = $service->find_by_no($no);
        $settings = [
            "no" => $no,
            "setting_title" => $unit->get_value("setting_title"),
            "payke_resource_id" => $unit->get_value("payke_resource_id"),
            "payke_tag_id" => $unit->get_value("payke_tag_id"),
            "payke_host_id" => $unit->get_value("payke_host_id"),
            "payke_enable_affiliate" => $unit->get_value("payke_enable_affiliate"),
            "payke_x_auth_token" => $unit->get_value("payke_x_auth_token")
        ];

        $hService = new PaykeHostService();
        $hosts = $hService->find_all_to_array();
        array_unshift($hosts, ["id" => null, "name" => "どこでもOK"]);

        $rService = new PaykeResourceService();
        $resources = $rService->find_all_to_array();

        $uService = new PaykeUserService();
        $tags = $uService->get_tags_array();
        array_unshift($tags, ["id" => null, "name" => "なし"]);

        return view('deploy_setting.edit', ['settings' => $settings, 'hosts' => $hosts, 'resources' => $resources, 'tags' => $tags]);
    }

    public function post_edit(EditRequest $request)
    {
        $service = new DeploySettingService();

        $service->edit($request->to_setting_models());

        session()->flash('successTitle', '接続設定を更新しました。');
        return redirect()->route('deploy_setting.index');
    }

    public function view_edit_base()
    {
        $service = new DeploySettingService();
        $bases = $service->find_base();
        $settings = [];
        foreach ($bases as $base) {
            $settings[$base->key] = $base->value;
        }
        return view('deploy_setting.edit_base', ['settings' => $settings]);
    }

    public function post_edit_base(EditBaseRequest $request)
    {
        $service = new DeploySettingService();

        $service->edit($request->to_setting_models());

        session()->flash('successTitle', '接続設定を更新しました。');
        return redirect()->route('deploy_setting.index');
    }
}
