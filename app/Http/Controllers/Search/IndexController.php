<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Models\DeployLog;
use App\Models\PaykeUser;
use App\Models\Job;
use App\Services\DeployService;
use App\Services\PaykeDbService;
use App\Services\PaykeHostService;
use App\Services\PaykeUserService;
use App\Services\PaykeResourceService;
use App\Services\DeployLogService;
use App\Services\SearchService;
use App\Jobs\DeployManyJob;
use Illuminate\Http\Request;
use Carbon\Carbon;

use function Laravel\Prompts\search;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $inputSearchWord = (string) $request->input('searchWord');
        $searchWords = explode(" ", $inputSearchWord);
        switch($searchWords[0])
        {
            case ':jobs_view' :
                $jobs = Job::all();
                dd($jobs);
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
                $is_success = $deployService->deploy($user->PaykeHost, $user, $user->PaykeDb, $user->PaykeResource, $outLog, false);
        
                $service = new PaykeUserService();
                if($is_success)
                {
                    $service->save_active($user);
                    return view('common.result', ["successTitle" => "成功！", "successMessage" => "Payke「{ $user->PaykeResource->version }」をデプロイしました！"]);
                } else {
                    $service->save_has_error($user,  implode("\n", $outLog));
                    return view('common.result', ["errorTitle" => "あちゃ〜、、失敗！", "errorMessage" => "Payke のデプロイに失敗しました！", "info" => $outLog]);
                }
            case ':re_deploy_first' :
                $user = PaykeUser::where('id', $searchWords[1])->firstOrFail();
                $deployService = new DeployService();
                $outLog = [];
                $is_success = $deployService->deploy($user->PaykeHost, $user, $user->PaykeDb, $user->PaykeResource, $outLog, true);
        
                $service = new PaykeUserService();
                if($is_success)
                {
                    $service->save_active($user);
                    return view('common.result', ["successTitle" => "成功！", "successMessage" => "Payke「{ $user->PaykeResource->version }」をデプロイしました！"]);
                } else {
                    $service->save_has_error($user,  implode("\n", $outLog));
                    return view('common.result', ["errorTitle" => "あちゃ〜、、失敗！", "errorMessage" => "Payke のデプロイに失敗しました！", "info" => $outLog]);
                }
            case ':d_many' : 
                $uService = new PaykeUserService();
                $rService = new PaykeResourceService();
                $payke = $rService->find_by_id($searchWords[1]); // 14 // 11 12,13
                $ids = explode(",", $searchWords[2]); // 13,15,22,24
                $users = $uService->find_by_ids($ids);
                $deployJob = (new DeployManyJob($users, $payke))->delay(Carbon::now()->addSeconds(1));
                dispatch($deployJob);
                return view('common.result', ["successTitle" => "デプロイ開始", "successMessage" => "Paykeのデプロイ開始しました。しばらくお待ちください。"]);
            case ':payke_v' : 
                $rService = new PaykeResourceService();
                $payke = $rService->find_by_id($searchWords[1]);
                $paykes = $rService->find_upper_version_to_array($payke);
                dd($paykes); 
            case ':test' :
                return view('common.result', ["successTitle" => "成功！", "successMessage" => "Payke v3.23.1 のデプロイに成功しました！",
                    "info" => ["task deploy:info",
                        "[payke_release] info deploying HEAD",
                        " task deploy:setup",
                        " task deploy:lock",
                        " task deploy:release:db_backup"]]);
            default:
                $service = new SearchService();
                $users = $service->search($inputSearchWord);
                return view('search.index', ['users' => $users, 'keyword' => $inputSearchWord]);
        }
        dd($searchWords);
    }
}
