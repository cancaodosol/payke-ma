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
        $user = new PaykeUser();
        $user->payke_host_id = $request->paykeHostId();
        $user->payke_db_id = $request->paykeDbId();
        $user->payke_resource_id = $request->paykeResourceId();
        $user->user_folder_id = "user_{$request->paykeHostId()}_{$request->paykeDbId()}";
        $user->user_app_name = $request->paykeAppName();
        $user->enable_affiliate = $request->enableAffiliate();
        $user->user_name = $request->userName();
        $user->email_address = $request->emailAddress();
        $user->memo = $request->memo();

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
        } else {
            $service->save_has_error($user,  implode("\n", $outLog));
        }

        dd($outLog);
        return redirect()->route('tweet.index');
    }
}