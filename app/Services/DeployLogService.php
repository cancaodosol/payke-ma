<?php

namespace App\Services;

use App\Models\DeployLog;
use App\Models\PaykeResource;
use App\Models\PaykeUser;

class DeployLogService
{
    public function write_log(int $status, PaykeUser $user, PaykeResource $resource, string $message, string $deployParam = '', array $deployerLogs = [])
    {
        $log = new DeployLog();
        $log->status = $status;
        $log->user_id = $user->id;
        $log->user_name = $user->user_name;
        $log->user_app_name = $user->user_app_name;
        $log->payke_version = $resource->version;
        $log->message = $message;
        $log->deploy_params = $deployParam;
        $log->deployer_log = implode("\n", $deployerLogs);
        $log->save();
    }

    public function write_ok_log(PaykeUser $user, PaykeResource $resource, string $message, string $deployParam = '', array $deployerLogs = [])
    {
        return $this->write_log(DeployLog::STATUS__OK, $user, $resource, $message, $deployParam, $deployerLogs);
    }

    public function write_error_log(PaykeUser $user, PaykeResource $resource, string $message, string $deployParam = '', array $deployerLogs = [])
    {
        return $this->write_log(DeployLog::STATUS__ERROR, $user, $resource, $message, $deployParam, $deployerLogs);
    }
}