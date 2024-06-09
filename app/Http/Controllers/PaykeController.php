<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payke\OrderRequest;
use App\Services\UserService;
use App\Factories\PaykeUserFactory;
use App\Services\DeployService;
use App\Services\DeploySettingService;
use App\Services\DeployLogService;
use App\Services\PaykeResourceService;
use App\Services\PaykeUserService;
use App\Services\PaykeApiService;
use App\Services\MailService;
use App\Jobs\DeployJob;
use App\Jobs\DeployJobOrderd;
use App\Helpers\SecurityHelper;
use App\Models\Job;
use App\Models\PaykeUser;
use App\Models\OrderCancelWaiting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Mail\Mailer;
use Carbon\Carbon;

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

    public function connect_paykeec_to_ma(OrderRequest $request, Mailer $mailer, int $no)
    {
        Log::info("Accessed in /payke/ec2ma/".$no.". \ndata ->");
        try
        {
            Log::info($request->raw());
            $request->to_payke_ec_order()->save();

            $dService = new DeploySettingService();
            $settingUnit = $dService->find_by_no($no);
            if(!$settingUnit)
            {
                Log::info("指定の設定Noが存在しません。");
                return;
            }
            if(!$settingUnit->match_x_auth_token($request->header("X-AUTH-TOKEN", "")))
            {
                Log::info("X-AUTH-TOKENが一致しませんでした。");
                return;
            }

            // [1]PaykeECの新規購入時
            if($request->is_type_paid_or_registered())
            {
                $order_id = $request->order_id();
                $user_name = $request->customer_full_name();
                $email_address = $request->customer_email();
    
                // ログインユーザー存在チェック
                $uService = new UserService();
                if($uService->exists_user($email_address)){

                    // ログインユーザーが存在した場合は、プラン変更データとして正しいかどうかをチェックする
                    $ser = new PaykeApiService();
                    $order = $ser->get_order($order_id);
                    if(!$order || $order->arg1 == "" ){
                        $title = "【Payke連携エラー】指定の注文形式でない注文IDのデータを受信";
                        $message = "受信した注文IDが見つからないか、指定の注文形式でありません。[ID:{$order_id}]";
                        $this->write_log_and_send_email($mailer, $title, $message, $request->raw());
                        return;
                    }
                    $pSer = new PaykeUserService();
                    $pUser = $pSer->find_by_uuid($order->get_uuid());
                    if(!$pUser || $pUser->User->email != $email_address){
                        $title = "【Payke連携エラー】PaykeUserが見つからないデータを受信";
                        $message = "受信したPaykeUserが見つからないか、メールアドレスが一致しません。[UUID:{$order->get_uuid()}]";
                        $this->write_log_and_send_email($mailer, $title, $message, $request->raw());
                        return;
                    }

                    // キャンセル待ちテーブルに保存
                    $logSer = new DeployLogService();
                    $logSer->write_warm_log($pUser, "プラン変更データ受信", "「{$settingUnit->get_value('setting_title')}」へのプラン変更データを受信しました。", null, "", $order->to_array_for_log());
                    $orderCancelWaiting = (new OrderCancelWaiting())->set_order($pUser, $order_id);
                    $orderCancelWaiting->save();

                    // 親Payke連携設定のnoに応じて、Payke環境の設定を変更
                    $old_order_id = $pUser->payke_order_id;
                    $pUser->deploy_setting_no = $no;
                    $pUser->payke_order_id = $order_id;
                    $pUser->enable_affiliate = $settingUnit->get_value("payke_enable_affiliate");
                    $pSer->edit($pUser->id, $pUser, false);

                    // 旧注文キャンセル
                    $canceled = $ser->cancel_order($old_order_id);
                    if($canceled){
                        $logSer->write_warm_log($pUser, "旧注文キャンセル", "プラン変更により、注文データが切り替わりますので、旧注文データへキャンセル処理を行いました。");
                    } else {
                        $logSer->write_error_log($pUser, "旧注文キャンセル失敗", "プラン変更による旧注文データへキャンセル処理が失敗しました。");
                    }

                    return;
                }
                
                // ログインユーザーの作成
                $new_password = SecurityHelper::create_ramdam_string(15);
                $user = $uService->save_user($user_name, $email_address, $new_password);
    
                // Paykeユーザーを仮作成。
                $factory = new PaykeUserFactory();
                $pUser = $factory->create_new_user($user_name, $email_address, $settingUnit);
                $pUser->user_id = $user->id;
                $pUser->payke_order_id = $order_id;
                $has_error = $pUser->payke_host_id == null;
    
                // DB保存。
                $service = new PaykeUserService();
                if($has_error)
                {
                    $service->save_has_error($pUser);
                    $title = "PaykeECデプロイリソース不足";
                    $message = "デプロイに必要な空きデータベースがなく、PaykeEC環境を作成できませんでした。\n\nuserid: ".$pUser->id;
                    $this->write_log_and_send_email($mailer, $title, $message, $request->raw());
                } else {
                    $service->save_init($pUser);
                }
        
                // ログ保存。
                $logService = new DeployLogService();
                $message = "Webwookから新規作成しました。";
                $logService->write_other_log($pUser, "新規作成", $message);

                if($has_error)
                {
                    $err_message = "デプロイに必要な空きデータベースがなく、PaykeEC環境を作成できませんでした。";
                    $logService->write_error_log($pUser, "リソース不足", $err_message);
                    return;
                }
        
                // Paykeのデプロイ開始。
                $deployJob = (new DeployJobOrderd($pUser->PaykeHost, $pUser, $pUser->PaykeDb, $pUser->PaykeResource, $email_address, $new_password))->delay(Carbon::now());
                dispatch($deployJob);
            } else {
                Log::info($request->data());
                Log::info($request->order_id());

                // [2]継続決済のサイクルごとの決済成功時
                // payment.succeeded
                if($request->is_type_payment_succeeded())
                {
                    // 特になし。継続してご利用ください。
                    return;
                }

                // プラン変更で起こったキャンセル処理の場合は、処理終了
                $orderCancelWaiting = $this->find_order_cancel_waiting($request->order_id());
                if($orderCancelWaiting && $request->is_type_order_canceled())
                {
                    $orderCancelWaiting->has_canceled = true;
                    $orderCancelWaiting->save();

                    $logSer = new DeployLogService();
                    $pSer = new PaykeUserService();
                    $pUser = $pSer->find_by_id($orderCancelWaiting->payke_user_id);
                    $logSer->write_success_log($pUser, "キャンセルデータ受信", "「{$settingUnit->get_value('setting_title')}」の注文キャンセルデータを受信しました。", null, "", [$request->raw()]);
                    return;
                }

                $service = new PaykeUserService();
                $user = $service->find_by_order_id($request->order_id());
                if(!$user)
                {
                    $title = "未登録ユーザーの決済データを受信";
                    $message = "未登録ユーザーの決済データを受信しました。\nPaykeECからの決済データに紐づくユーザーがありません。";
                    $this->write_log_and_send_email($mailer, $title, $message, $request->raw());
                    return;
                }
                Log::info($user->user_name);

                // [3]継続決済の支払停止時（お客さんの支払いが滞っている時）
                // order.payment_stopped
                if($request->is_type_payment_stopped())
                {
                    // ステータスを未払いにする
                    $user->status = PaykeUser::STATUS__HAS_UNPAID;
                    $service->edit($user->id, $user, false);
                }

                // [4]継続決済の支払再開時
                // order.payment_restarted
                if($request->is_type_payment_restarted())
                {
                    // ステータスを正常稼働にする
                    $user->status = PaykeUser::STATUS__ACTIVE;
                    $service->edit($user->id, $user, false);
                }

                // [5]キャンセル時（お客さんの意思で、アプリの利用を辞めたい時）
                // order.canceled
                if($request->is_type_order_canceled())
                {
                    $user->status = PaykeUser::STATUS__UNUSED;
                    $service->edit($user->id, $user, false);
                }

                // [6]決済完了時（利用期間満了で、継続支払い終了）
                // order.placed
                if($request->is_type_placed())
                {
                    $user->status = PaykeUser::STATUS__UNUSED;
                    $service->edit($user->id, $user, false);
                }
            }
        }
        catch(Exception $e)
        {
            $title = "想定外のエラー";
            $message = "PaykeECからのデータ連携で、想定外のエラーが発生しました。\n\n".$e->getMessage();
            $this->write_log_and_send_email($mailer, $title, $message, $request->raw());
        }
    }

    public function find_order_cancel_waiting(int $order_id)
    {
        return OrderCancelWaiting::where([
                ["order_id", "=", $order_id],
                ["is_active", "=", true],
                ["has_canceled", "=", false]
            ])->first();
    }

    public function write_log_and_send_email(Mailer $mailer, string $title, string $message, $jsondata = [], array $log = [])
    {
        Log::error("▼ ".$title."\n".$message);
        $mService = new MailService($mailer);
        $mService->send_to_admin($title, $message, $jsondata, $log);

    }
}