<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\DeployService;
use App\Services\PaykeResourceService;
use App\Services\PaykeUserService;
use App\Jobs\DeployJob;
use App\Models\Job;
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
            return view('common.result', ["warnTitle" => "設定済み", "warnMessage" => "すでにアフィリエイト機能は、{$label}でした。"]);
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
        return view('common.result', ["successTitle" => "設定完了！", "successMessage" => "アフィリエイト機能を{$label}にしました。"]);
    }

    public function post_edit_version(Request $request)
    {
        $uService = new PaykeUserService();
        $user = $uService->find_by_id($request->input('user_id'));
        $rService = new PaykeResourceService();
        $payke = $rService->find_by_id($request->input('payke_resource'));

        $uService->save_update_waiting($user);
        $deployJob = (new DeployJob($user->PaykeHost, $user, $user->PaykeDb, $payke, false))->delay(Carbon::now());
        dispatch($deployJob);

        session()->flash('successTitle', 'アップデート開始');
        session()->flash('successMessage', "「 $user->user_name 」のPaykeを「 $payke->version 」にアップデートしております。1 ~ 2 分お待ちください。");
        return redirect()->route('payke_user.index');
    }

    public function api_get_jobqueue()
    {
        $jobs = Job::all();
        $ret = [];
        foreach ($jobs as $job) {
            $ret[] = $job->to_array();
        }
        return response()->json(["jobs" => $ret]);
    }
}