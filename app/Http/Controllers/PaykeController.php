<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\DeployService;
use App\Services\PaykeResourceService;
use App\Services\PaykeUserService;
use App\Jobs\DeployJob;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaykeController extends Controller
{
    public function post_edit_affiliate(Request $request)
    {
        $user_id = $request->input('user_id');
        $enable_affiliate = $request->input('enable_affiliate');

        $service = new PaykeUserService();
        $user = $service->find_by_id($user_id);

        $label = $enable_affiliate == 1 ? "有効" : "無効";
        if($user->enable_affiliate == $enable_affiliate)
        {
            return view('common.result', ["title" => "設定済み", "message" => "すでにアフィリエイト機能は、{$label}でした。"]);
        }

        $r = null;
        if($enable_affiliate == 1)
        {
            $r = $service->open_affiliate($user);
        }
        else
        {
            $r = $service->close_affiliate($user);
        }
        return view('common.result', ["title" => "設定完了！", "message" => "アフィリエイト機能を{$label}にしました。"]);
    }

    public function post_edit_version(Request $request)
    {
        $uService = new PaykeUserService();
        $user = $uService->find_by_id($request->input('user_id'));
        $rService = new PaykeResourceService();
        $payke = $rService->find_by_id($request->input('payke_resource'));

        $outLog = [];
        $deployJob = (new DeployJob($user->PaykeHost, $user, $user->PaykeDb, $payke, false))->delay(Carbon::now()->addSeconds(1));
        dispatch($deployJob);

        return view('common.result', ["title" => "Paykeのデプロイを開始しました。", "message" => "Paykeのデプロイ開始しました。しばらくお待ちください。"]);
    }
}