<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payke\OrderRequest;
use App\Factories\PaykeUserFactory;
use App\Services\DeployService;
use App\Services\DeployLogService;
use App\Services\PaykeResourceService;
use App\Services\PaykeUserService;
use App\Jobs\DeployJob;
use App\Models\Job;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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

    public function connect_paykeec_to_ma(OrderRequest $request)
    {
        Log::info("Accessed in /payke/ec2ma. \ndata ->");
        try
        {
            Log::info("order_id : ".$request->order_id());
            Log::info($request->raw());

            // Paykeユーザーを仮作成。
            $factory = new PaykeUserFactory();
            $user_name = $request->customer_full_name();
            $email_address = $request->customer_email();
            $user = $factory->create_new_user($user_name, $email_address);

            // DB保存。
            $service = new PaykeUserService();
            $service->save_init($user);

            // ログ保存。
            $logService = new DeployLogService();
            $message = "Webwookから新規作成しました。";
            $logService->write_other_log($user, "新規作成", $message);
    
            // Paykeのデプロイ開始。
            $deployJob = (new DeployJob($user->PaykeHost, $user, $user->PaykeDb, $user->PaykeResource, true))->delay(Carbon::now());
            dispatch($deployJob);

        }
        catch(Exception $e)
        {
            Log::error($e->getMessage());
        }
    }
}