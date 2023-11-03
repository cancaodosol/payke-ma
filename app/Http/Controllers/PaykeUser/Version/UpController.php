<?php

namespace App\Http\Controllers\PaykeUser\Version;

use App\Http\Controllers\Controller;
use App\Models\PaykeDb;
use App\Models\PaykeHost;
use App\Models\PaykeResource;
use App\Services\DeployService;
use App\Services\PaykeDbService;
use App\Services\PaykeResourceService;
use App\Services\PaykeUserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UpController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $uService = new PaykeUserService();
        $user = $uService->find_by_id($request->input('user_id'));
        $rService = new PaykeResourceService();
        $payke = $rService->find_by_id($request->input('payke_resource'));

        $dService = new DeployService();
        $outLog = [];
        $is_success = $dService->deploy($user->PaykeHost, $user, $user->PaykeDb, $payke, $outLog, false);

        if($is_success)
        {
            $user->payke_resource_id = $payke->id;
            $uService->save_active($user);
            return view('common.result', ["title" => "成功！", "message" => "Payke のデプロイに成功しました！"]);
        } else {
            $uService->save_has_error($user,  implode("\n", $outLog));
            return view('common.result', ["title" => "あちゃ〜、、失敗！", "message" => "Payke のデプロイに失敗しました！", "info" => $outLog]);
        }
    }
}
