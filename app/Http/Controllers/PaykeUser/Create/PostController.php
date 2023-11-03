<?php

namespace App\Http\Controllers\PaykeUser\Create;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaykeUser\CreateRequest;
use App\Models\PaykeUser;
use App\Services\DeployService;
use App\Services\PaykeUserService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(CreateRequest $request)
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
}