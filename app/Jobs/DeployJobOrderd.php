<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

use App\Services\PaykeUserService;
use App\Services\DeployService;
use App\Models\DeployLog;
use App\Models\PaykeDb;
use App\Models\PaykeHost;
use App\Models\PaykeResource;
use App\Models\PaykeUser;
use App\Mail\PaykeEcOrderdMail;
use App\Helpers\SecurityHelper;

class DeployJobOrderd implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public DeployService $deployService;
    public PaykeUserService $paykeUserService;
    public PaykeHost $host;
    public PaykeUser $user;
    public PaykeDb $db;
    public PaykeResource $payke;

    public string $admin_username;
    public string $admin_email;
    public string $admin_password;

    public $timeout = 180;

    /**
     * Create a new job instance.
     */
    public function __construct(PaykeHost $host, PaykeUser $user, PaykeDb $db, PaykeResource $payke, string $admin_username, string $admin_email, string $admin_password)
    {
        $this->deployService = new DeployService();
        $this->paykeUserService = new PaykeUserService();
        $this->host = $host;
        $this->user = $user;
        $this->db = $db;
        $this->payke = $payke;
        $this->admin_username = $admin_username;
        $this->admin_email = $admin_email;
        $this->admin_password = $admin_password;
    }

    /**
     * Execute the job.
     */
    public function handle(Mailer $mailer): void
    {
        try
        {
            $this->paykeUserService->save_updating_now($this->user);

            $outLog = [];
            $is_success = $this->deployService->deploy($this->host, $this->user, $this->db, $this->payke, $outLog, true);

            if($is_success)
            {
                $this->user->payke_resource_id = $this->payke->id;
                $this->paykeUserService->save_wait_setting($this->user);

                // メンテナンス用ユーザーの作成。
                $superadmin_username = "U".SecurityHelper::create_ramdam_string(25);
                $superadmin_password = "P".SecurityHelper::create_ramdam_string(25);

                $outLog = [];
                $is_success = $this->deployService->replace_admin_to_superadmin($this->user, $superadmin_username, $superadmin_password, $outLog);
                if($is_success)
                {
                    $this->user->superadmin_username = $superadmin_username;
                    $this->user->superadmin_password = $superadmin_password;
                    $this->user->save();
                } else {

                }

                // 購入者用のユーザーの作成。
                // MEMO: 初回のPaykeMAのログイン情報と、Paykeのログイン情報は同じにする。
                $outLog = [];
                $is_success = $this->deployService->create_admin_user($this->user, $this->admin_username, $this->admin_email, $this->admin_password, $outLog);

                // ログイン情報をユーザーへ通知。
                if($is_success)
                {
                    $mailer->to($this->user->email_address)
                        ->send(new PaykeEcOrderdMail($this->user, $this->admin_username, $this->admin_password, route("login")));
                } else {

                }
            } else {
                $this->paykeUserService->save_has_error($this->user, implode("\n", $outLog));

                // TODO: 管理者にメールを送信。
            }
        }
        catch(Exception $e)
        {
            Log::error($e->getMessage());
        }
    }
}
