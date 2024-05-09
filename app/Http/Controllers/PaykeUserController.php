<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaykeUser\CreateRequest;
use App\Models\PaykeUser;
use App\Models\PaykeEcOrder;
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
        $orders = PaykeEcOrder::where('order_id', $user->payke_order_id)->orderByRaw('created_at DESC, id DESC')->get();
        return view('payke_user.profile', ['user' => $user, 'payke_ec_orders' => $orders]);
    }

    public function view_by_tag_id(int $tagId)
    {
        $allusers = PaykeUser::sortable()->get();
        $users = [];
        foreach ($allusers as $user) {
            if(!$user->Tag || $user->Tag->id != $tagId) continue;
            $users[] = $user;
        }

        $rService = new PaykeResourceService();
        $resources = $rService->find_all_to_array();

        $uService = new PaykeUserService();
        $tags = $uService->get_tags();

        return view('payke_user.index', ['users' => $users, 'resources' => $resources, 'tags' => $tags]);
    }

    public function view_all(Request $request)
    {
        $allusers = PaykeUser::sortable()->get();
        $users = [];
        foreach ($allusers as $user) {
            if($user->Tag && $user->Tag->is_hidden) continue;
            $users[] = $user;
        }

        $rService = new PaykeResourceService();
        $resources = $rService->find_all_to_array();

        $uService = new PaykeUserService();
        $tags = $uService->get_tags();

        return view('payke_user.index', ['users' => $users, 'resources' => $resources, 'tags' => $tags]);
    }

    public function view_by_payke_id(int $paykeId)
    {
        $users = PaykeUser::Where('payke_resource_id', $paykeId)->get();
        $rService = new PaykeResourceService();
        $resources = $rService->find_all_to_array();
        return view('payke_user.index', ['users' => $users, 'resources' => $resources]);
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
        if($service->exists_same_name($user->payke_host_id, $user->user_app_name))
        {
            $resService = new PaykeResourceService();
            $resources = $resService->find_all_to_array();
            $dbService = new PaykeDbService();
            $host_dbs = $dbService->find_ready_host_dbs();
            session()->flash('errorTitle', '入力内容に問題があります。');
            session()->flash('errorMessage', "公開アプリ名「{$user->user_app_name}」は既に使用されております。別の名前でご登録ください。");
            return view('payke_user.create', ["host_dbs" => $host_dbs, "resources" => $resources, "user" => $user]);
        }
        $service->save_init($user);

        $logService = new DeployLogService();
        $message = "新規作成しました。";
        $logService->write_other_log($user, "新規作成", $message);
        
        // Paykeのデプロイ開始。
        $deployJob = (new DeployJob($user->PaykeHost, $user, $user->PaykeDb, $user->PaykeResource, true))->delay(Carbon::now());
        dispatch($deployJob);

        return view('common.result', ["successTitle" => "Paykeのデプロイを開始しました。", "successMessage" => "Paykeのデプロイ開始しました。しばらくお待ちください。"]);
    }

    public function view_edit(int $id)
    {
        $service = new PaykeUserService();
        $statuses = $service->get_statuses();
        $tags = $service->get_tags_array();
        array_unshift($tags, ["id" => null, "name" => "-"]);
        $user = $service->find_by_id($id);

        $resService = new PaykeResourceService();
        $resources = $resService->find_all_to_array();

        $host_dbs = [];
        if($user->payke_host_id == null)
        {
            $dbService = new PaykeDbService();
            $host_dbs = $dbService->find_ready_host_dbs();
            array_unshift($host_dbs, ["id" => null, "name" => "-"]);
        } else {
            $host_dbs = [[
                "id" => $user->host_db_id(),
                "name" => "{$user->PaykeHost->name} / {$user->PaykeDb->db_database}"
            ]];
        }

        return view('payke_user.edit', ["user" => $user, "statuses" => $statuses, "tags" => $tags, "host_dbs" => $host_dbs, "resources" => $resources]);
    }

    public function post_edit(CreateRequest $request)
    {
        $service = new PaykeUserService();

        $id = $request->input("id");
        $currentUser = $service->find_by_id($id);
        $newUser = $request->to_payke_user();

        if($currentUser->user_app_name != $newUser->user_app_name && $service->exists_same_name($newUser->payke_host_id, $newUser->user_app_name))
        {
            $statuses = $service->get_statuses();
            $resService = new PaykeResourceService();
            $resources = $resService->find_all_to_array();
            $dbService = new PaykeDbService();
            $host_dbs = [[
                "id" => $newUser->host_db_id(),
                "name" => "{$newUser->PaykeHost->name} / {$newUser->PaykeDb->db_database}"
            ]];
            session()->flash('errorTitle', '入力内容に問題があります。');
            session()->flash('errorMessage', "公開アプリ名「{$newUser->user_app_name}」は既に使用されております。別の名前でご登録ください。");
            return view('payke_user.edit', ["user" => $newUser, "statuses" => $statuses, "host_dbs" => $host_dbs, "resources" => $resources]);
        }

        $service->edit($id, $newUser);

        session()->flash('successTitle', '利用者情報を更新しました！');
        session()->flash('successMessage', "");
        return redirect()->route('payke_user.profile', ['userId' => $id]);
    }

    public function post_memo_edit(Request $request)
    {
        $service = new PaykeUserService();

        $id = $request->input("id");
        $memo = $request->input("memo");
        $user = $service->find_by_id($id);
        $user->memo = $memo;

        $service->edit($id, $user);

        session()->flash('successTitle', 'メモを更新しました！');
        session()->flash('successMessage', "");
        return redirect()->route('payke_user.profile', ['userId' => $id]);
    }

    public function api_get_user($userId)
    {
        $service = new PaykeUserService();
        $user = $service->find_by_id($userId);
        return response()->json(['user' => $user->to_array()]);
    }
}