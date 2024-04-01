<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Mail\NewUserIntroduction;
use App\Mail\PaykeEcOrderdMail;
use App\Mail\ErrorMail;
use App\Models\DeployLog;
use App\Models\PaykeUser;
use App\Models\User;
use App\Models\Job;
use App\Models\FailedJob;
use App\Models\PaykeEcOrder;
use App\Models\DeploySetting;
use App\Services\UserService;
use App\Services\DeployService;
use App\Services\PaykeDbService;
use App\Services\PaykeHostService;
use App\Services\PaykeUserService;
use App\Services\PaykeResourceService;
use App\Services\DeployLogService;
use App\Services\SearchService;
use App\Services\MailService;
use App\Factories\PaykeUserFactory;
use App\Helpers\SecurityHelper;
use App\Jobs\DeployJob;
use App\Jobs\DeployJobOrderd;
use App\Jobs\DeployManyJob;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

use function Laravel\Prompts\search;

class IndexController extends Controller
{
    public function __invoke(Request $request, Mailer $mailer)
    {
        $inputSearchWord = (string) $request->input('searchWord');
        $searchWords = explode(" ", $inputSearchWord);
        Log::info("search by ".$inputSearchWord);
        switch($searchWords[0])
        {
            case ':jobs_view' :
                $jobs = Job::all();
                dd($jobs);
            case ':failed_jobs_view' :
                $jobs = FailedJob::all();
                dd($jobs);
            case ':settings_view' :
                $settings = DeploySetting::all();
                dd($settings);
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
            case ':eclogs_view' :
                $logs = PaykeEcOrder::all();
                dd($logs);
            case ':send_email' :
                $mailser = new MailService($mailer);
                $user = PaykeUser::where('id', 24)->firstOrFail();
                // $user->payke_order_id = "1234";
                // $mailer->to($user->email_address)
                //     ->send(new PaykeEcOrderdMail($user, "ぉぎん", "hahaha", route("login")));
                // $mailer->to($user->email_address)
                //     ->send(new ErrorMail("データなし", "テストでエラーメッセージを送ってみました。"));
                $mailser->send_to_admin("サービスから", "送ってみたよ。");
            case ':set_user' :
                $user = User::where('id', 2)->firstOrFail();
                // $pUser = PaykeUser::where('id', 30)->firstOrFail();
                // $pUser->user_id = $user->id;
                // $pUser->save();
                $user->role = 2;
                $user->save();
                dd($user);
            case ':users_view' :
                if(count($searchWords) > 1)
                {
                    $user = User::where('id', $searchWords[1])->firstOrFail();
                    dd($user);
                }else{
                    $users = User::all();
                    dd($users);
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
            case ':paykeusers_view' :
                if(count($searchWords) > 1)
                {
                    $user = PaykeUser::where('id', $searchWords[1])->firstOrFail();
                    $data = [
                        "user_name" => $user->user_name,
                        "payke_order_id" => $user->payke_order_id,
                    ];
                    dd($data);
                }else{
                    $users = PaykeUser::all();
                    $data = [];
                    foreach($users as $user)
                    {
                        $data[] = [
                            "user_name" => $user->user_name,
                            "payke_order_id" => $user->payke_order_id,
                        ];
                    }
                    dd($data);
                }
            case ':user_wiew_order_id' :
                if(count($searchWords) != 2) dd("user_wiew_order_id <order_id>");
                $uService = new PaykeUserService();
                $user = $uService->find_by_order_id($searchWords[1]);
                dd($user);
            case ':lock_users' :
                // 管理ユーザーの取得
                $uService = new PaykeUserService();
                $pUser = $uService->find_by_id(40);
                // Paykeのデプロイ開始。
                $outLog = [];
                $deployService = new DeployService();
                $deployService->lock_users($pUser, $outLog);
                dd($outLog);
                return;
            case ':unlock_users' :
                // 管理ユーザーの取得
                $uService = new PaykeUserService();
                $pUser = $uService->find_by_id(40);
                // Paykeのデプロイ開始。
                $outLog = [];
                $deployService = new DeployService();
                $deployService->unlock_users($pUser, $outLog);
                dd($outLog);
                return;
            case ':hash' :
                if(count($searchWords) != 2) return;
                dd(SecurityHelper::create_hashed_password($searchWords[1]));
            case ':stop_app' :
                $uService = new PaykeUserService();
                $pUser = $uService->find_by_id(40);

                $outLog = [];
                $deployService = new DeployService();
                $deployService->stop_app($pUser, $outLog);
                dd($outLog);
                return;
            case ':restart_app' :
                $uService = new PaykeUserService();
                $pUser = $uService->find_by_id(40);

                $outLog = [];
                $deployService = new DeployService();
                $deployService->restart_app($pUser, $outLog);
                dd($outLog);
                return;
            case ':c' :
                $uService = new PaykeUserService();
                $user = $uService->find_by_id(15);
                $deployService = new DeployService();
                $outLog = [];
                $is_success = $deployService->create_admin_user($user, "atagohan@yahoo.co.jp", "matsui^^^3", $outLog);
                dd($outLog);
                return;
            case ':d' :
                $uService = new PaykeUserService();
                $user = $uService->find_by_id(15);
                $deployService = new DeployService();
                $outLog = [];
                $is_success = $deployService->update_superadmin_password($user, "superadmin", $outLog);
                dd($outLog);
                return;
            case ':f' :
                $user_name = "test10";
                $email_address = "test10@bbb.com";
                $order_id = "500001";
                $new_password = SecurityHelper::create_ramdam_string();
                Log::info("ユーザー名: ".$user_name."、 メールアドレス: ".$email_address."、 パスワード: ".$new_password);

                // ログインユーザーを作成する
                $uService = new UserService();
                $user = $uService->save_user($user_name, $email_address, $new_password);

                // Paykeユーザーを仮作成。
                $factory = new PaykeUserFactory();
                $pUser = $factory->create_new_user($user_name, $email_address);
                $pUser->user_id = $user->id;
                $pUser->payke_order_id = $order_id;

                // DB保存。
                $service = new PaykeUserService();
                $service->save_init($pUser);
        
                // ログ保存。
                $logService = new DeployLogService();
                $message = " Searchから新規作成しました。";
                $logService->write_other_log($pUser, "新規作成", $message);
        
                // Payke環境のデプロイ開始。
                $deployJob = (new DeployJob($pUser->PaykeHost, $pUser, $pUser->PaykeDb, $pUser->PaykeResource, true))->delay(Carbon::now());
                dispatch($deployJob);
            default:
                $service = new SearchService();
                $users = $service->search($inputSearchWord);
                $rService = new PaykeResourceService();
                $resources = $rService->find_all_to_array();
                return view('search.index', ['users' => $users, 'resources' => $resources, 'keyword' => $inputSearchWord]);
        }
        dd($searchWords);
    }
}
