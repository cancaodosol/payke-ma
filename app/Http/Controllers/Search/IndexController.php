<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Models\DeployLog;
use App\Models\PaykeUser;
use App\Services\DeployService;
use App\Services\PaykeDbService;
use App\Services\PaykeHostService;
use App\Services\PaykeUserService;
use App\Services\PaykeResourceService;
use App\Services\DeployLogService;
use Illuminate\Http\Request;

use function Laravel\Prompts\search;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $searchWords = explode(" ", (string) $request->input('searchWord'));
        switch($searchWords[0])
        {
            case ':newuser' :
                return redirect()->route('payke_user.create');
            case ':newhost' :
                return view('payke_host.create');
            case ':newdb' :
                $hostService = new PaykeHostService();
                $hosts = $hostService->find_all_to_array();
                return view('payke_db.create', ["hosts" => $hosts]);
            case ':editdb' :
                $hostService = new PaykeHostService();
                $hosts = $hostService->find_all_to_array();
                $service = new PaykeDbService();
                $db = $service->find_by_id($searchWords[1]);
                return view('payke_db.edit', ["db" => $db, "hosts" => $hosts]);
            case ':newpayke' :
                return view('payke_resource.create');
            case ':logs' :
                $deployLog = DeployLog::all()[0];
                dd($deployLog->getDiffTime());
            case ':logs_view' :
                if(count($searchWords) > 1)
                {
                    $ll = DeployLog::where('id', $searchWords[1])->firstOrFail();
                    dd([
                        "main" => "{$ll->created_at}   [{$ll->id}({$ll->type})] :   [{$ll->user_id}: {$ll->user_name}] {$ll->title}    {$ll->message}",
                        "log" => $ll->deployer_log
                    ]);
                }else{
                    $deployLogs = DeployLog::all()->sortByDesc('created_at');
                    $logs = [];
                    foreach($deployLogs as $ll)
                    {
                        $logs[] = "{$ll->created_at}   [{$ll->id}({$ll->type})] :   [{$ll->user_id}: {$ll->user_name}] {$ll->title}    {$ll->message}";
                    }
                    dd($logs);
                }
            case ':unlock' :
                $user = PaykeUser::where('id', $searchWords[1])->firstOrFail();
                $deploy = new DeployService();
                $logs = [];
                $deploy->unlock($user->PaykeHost, $user, $user->PaykeDb, $user->PaykeResource, $logs);                

                $service = new DeployLogService();
                $logs = $service->find_by_user_id($user->id);
                $rService = new PaykeResourceService();
                $resources = $rService->find_all_to_array();
                return view('deploy_log.index', ['user_id' => $user->id, 'logs' => $logs, 'resources' => $resources]);
            case ':re_deploy' :
                $user = PaykeUser::where('id', $searchWords[1])->firstOrFail();
                $deployService = new DeployService();
                $outLog = [];
                $is_success = $deployService->deploy($user->PaykeHost, $user, $user->PaykeDb, $user->PaykeResource, $outLog, true);
        
                $service = new PaykeUserService();
                if($is_success)
                {
                    $service->save_active($user);
                    return view('common.result', ["title" => "成功！", "message" => "Payke のデプロイに成功しました！"]);
                } else {
                    $service->save_has_error($user,  implode("\n", $outLog));
                    return view('common.result', ["title" => "あちゃ〜、、失敗！", "message" => "Payke のデプロイに失敗しました！", "info" => $outLog]);
                }
            case ':test' :
                return view('common.result', ["title" => "成功！", "message" => "Payke v3.23.1 のデプロイに成功しました！",
                    "info" => ["task deploy:info",
                        "[payke_release] info deploying HEAD",
                        " task deploy:setup",
                        " task deploy:lock",
                        " task deploy:release:db_backup"]]);
        }
        dd($searchWords);
    }
}
