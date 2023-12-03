<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

use App\Jobs\DeployJob;
use App\Services\PaykeUserService;
use App\Services\DeployService;
use App\Models\DeployLog;
use App\Models\PaykeDb;
use App\Models\PaykeHost;
use App\Models\PaykeResource;
use App\Models\PaykeUser;

class DeployManyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public DeployService $deployService;
    public PaykeUserService $paykeUserService;
    public $users;
    public PaykeResource $payke;

    /**
     * Create a new job instance.
     */
    public function __construct($users, PaykeResource $payke)
    {
        $this->deployService = new DeployService();
        $this->paykeUserService = new PaykeUserService();
        $this->users = $users;
        $this->payke = $payke;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->users as $user) {
            $this->paykeUserService->save_update_waiting($user);
        }

        foreach ($this->users as $user) {
            $deployJob = (new DeployJob($user->PaykeHost, $user, $user->PaykeDb, $this->payke, false))->delay(Carbon::now()->addSeconds(1));
            dispatch($deployJob);
        }
    }
}
