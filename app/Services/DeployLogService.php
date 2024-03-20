<?php

namespace App\Services;

use App\Models\DeployLog;
use App\Models\PaykeResource;
use App\Models\PaykeUser;

class DeployLogService
{
    public function write_log(int $type, PaykeUser $user, string $title, string $message, PaykeResource $resource = null, string $deployParam = '', array $deployerLogs = [])
    {
        $log = new DeployLog();
        $log->type = $type;
        $log->user_id = $user->id;
        $log->user_name = $user->user_name;
        $log->user_app_name = $user->user_app_name;
        $log->title = $title;
        $log->message = $message;
        $log->payke_resource_id = $resource == null ? null : $resource->id;
        $log->deploy_params = $deployParam;
        $log->deployer_log = implode("\n", $deployerLogs);
        $log->save();
    }

    public function write_version_log(PaykeUser $user, string $title, string $message, PaykeResource $resource = null, string $deployParam = '', array $deployerLogs = [])
    {
        return $this->write_log(DeployLog::TYPE__VERSION_INFO, $user, $title, $message, $resource, $deployParam, $deployerLogs);
    }

    public function write_error_log(PaykeUser $user, string $title, string $message, PaykeResource $resource = null, string $deployParam = '', array $deployerLogs = [])
    {
        return $this->write_log(DeployLog::TYPE__ERROR, $user, $title, $message, $resource, $deployParam, $deployerLogs);
    }

    public function write_other_log(PaykeUser $user, string $title, string $message, PaykeResource $resource = null, string $deployParam = '', array $deployerLogs = [])
    {
        return $this->write_log(DeployLog::TYPE__OTHER_INFO, $user, $title, $message, $resource, $deployParam, $deployerLogs);
    }

    public function find_by_id(int $id)
    {
        return DeployLog::where('id', $id)->firstOrFail();
    }

    public function find_by_user_id(int $user_id)
    {
        return DeployLog::where('user_id', $user_id)->orderByRaw('created_at DESC, id DESC')->get();
    }

    public function edit(int $id, array $values)
    {
        $log = $this->find_by_id($id);
        $log->update($values);
    }
}