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
use Illuminate\Http\Request;

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
        $service->save_init($user);
        
        // Paykeのデプロイ開始。
        $deployService = new DeployService();
        $outLog = [];
        $is_success = $deployService->deploy($user->PaykeHost, $user, $user->PaykeDb, $user->PaykeResource, $outLog, true);

        if($is_success)
        {
            $service->save_active($user);
            return view('common.result', ["title" => "成功！", "message" => "Payke のデプロイに成功しました！"]);
        } else {
            $service->save_has_error($user,  implode("\n", $outLog));
            return view('common.result', ["title" => "あちゃ〜、、失敗！", "message" => "Payke のデプロイに失敗しました！", "info" => $outLog]);
        }
    }

    public function view_edit(int $id)
    {
        return;
    }

    public function post_edit(CreateRequest $request)
    {
        return;
    }
}