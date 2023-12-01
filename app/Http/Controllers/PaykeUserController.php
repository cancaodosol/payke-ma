<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaykeUser\CreateRequest;
use App\Models\PaykeUser;
use App\Services\DeployService;
use App\Services\PaykeDbService;
use App\Services\PaykeHostService;
use App\Services\PaykeResourceService;
use App\Services\PaykeUserService;
use App\Services\DeployLogService;
use App\Jobs\DeployJob;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaykeUserController extends Controller
{
    public function view_one($userId)
    {
        $service = new PaykeUserService();
        $user = $service->find_by_id($userId);
        return view('payke_user.profile', ['user' => $user]);
    }

    public function view_all(Request $request)
    {
        $users = PaykeUser::all();
        return view('payke_user.index', ['users' => $users]);
    }

    public function view_by_payke_id(int $paykeId)
    {
        $users = PaykeUser::Where('payke_resource_id', $paykeId)->get();
        return view('payke_user.index', ['users' => $users]);
    }

    public function view_add(Request $request)
    {
        $resService = new PaykeResourceService();
        $resources = $resService->find_all_to_array();

        // MEMO: HostとDBは、一連なので、同じ項目として扱う。
        //       登録IDは、登録時に分裂させる。
        $dbService = new PaykeDbService();
        $host_dbs = $dbService->find_ready_host_dbs();

        return view('payke_user.create', ["host_dbs" => $host_dbs, "resources" => $resources]);
    }

    public function post_add(CreateRequest $request)
    {
        $user = $request->to_payke_user();

        // いったん、ここでユーザーは登録する。
        // 指定したDBは、このタイミングで使用中にする。デプロイエラーが起こっても、そのDBは確保。
        $service = new PaykeUserService();
        if($service->exists_same_name($user->user_app_name))
        {
            $resService = new PaykeResourceService();
            $resources = $resService->find_all_to_array();
            $dbService = new PaykeDbService();
            $host_dbs = $dbService->find_ready_host_dbs();
            return view('payke_user.create', ["host_dbs" => $host_dbs, "resources" => $resources, "user" => $user,
                "errorTitle" => "入力内容に問題があります。",
                "errorMessage" => "公開アプリ名「{$user->user_app_name}」は既に使用されております。別の名前でご登録ください。"]);
        }
        $service->save_init($user);

        $logService = new DeployLogService();
        $message = "新規作成しました。";
        $logService->write_other_log($user, "新規作成", $message);
        
        // Paykeのデプロイ開始。
        $deployJob = (new DeployJob($user->PaykeHost, $user, $user->PaykeDb, $user->PaykeResource, true))->delay(Carbon::now()->addSeconds(1));
        dispatch($deployJob);

        return view('common.result', ["title" => "Paykeのデプロイを開始しました。", "message" => "Paykeのデプロイ開始しました。しばらくお待ちください。"]);
    }

    public function view_edit(int $id)
    {
        $service = new PaykeUserService();
        $statuses = $service->get_statuses();
        $user = $service->find_by_id($id);

        $resService = new PaykeResourceService();
        $resources = $resService->find_all_to_array();

        $host_dbs = [[
            "id" => $user->host_db_id(),
            "name" => "{$user->PaykeHost->name} / {$user->PaykeDb->db_database}"
            ]];

        return view('payke_user.edit', ["user" => $user, "statuses" => $statuses, "host_dbs" => $host_dbs, "resources" => $resources]);
    }

    public function post_edit(CreateRequest $request)
    {
        $service = new PaykeUserService();

        $id = $request->input("id");
        $newUser = $request->to_payke_user();

        $service->edit($id, $newUser);

        $user = $service->find_by_id($id);
        return view('payke_user.profile', ['user' => $user]);
    }

    public function post_memo_edit(Request $request)
    {
        $service = new PaykeUserService();

        $id = $request->input("id");
        $memo = $request->input("memo");
        $user = $service->find_by_id($id);
        $user->memo = $memo;

        $service->edit($id, $user);

        $user = $service->find_by_id($id);
        return view('payke_user.profile', ['user' => $user, 'successTitle' => 'メモを更新しました！']);
    }
}