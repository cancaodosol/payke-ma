<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Services\PaykeUserService;
use App\Services\DeployService;
use App\Models\DeployLog;
use App\Models\PaykeDb;
use App\Models\PaykeHost;
use App\Models\PaykeResource;
use App\Models\PaykeUser;

class DeployJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public DeployService $deployService;
    public PaykeUserService $paykeUserService;
    public PaykeHost $host;
    public PaykeUser $user;
    public PaykeDb $db;
    public PaykeResource $payke;
    public bool $is_first;

    public $timeout = 180;

    /**
     * Create a new job instance.
     */
    public function __construct(PaykeHost $host, PaykeUser $user, PaykeDb $db, PaykeResource $payke, bool $is_first = false)
    {
        $this->deployService = new DeployService();
        $this->paykeUserService = new PaykeUserService();
        $this->host = $host;
        $this->user = $user;
        $this->db = $db;
        $this->payke = $payke;
        $this->is_first = $is_first;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->paykeUserService->save_updating_now($this->user);

        $outLog = [];
        $is_success = $this->deployService->deploy($this->host, $this->user, $this->db, $this->payke, $outLog, $this->is_first);

        if($is_success)
        {
            $this->user->payke_resource_id = $this->payke->id;
            if($this->is_first)
            {
                $this->paykeUserService->save_wait_setting($this->user);
            } else {
                $this->paykeUserService->save_active($this->user);
            }
        } else {
            $this->paykeUserService->save_has_error($this->user,  implode("\n", $outLog));
        }
    }
}
