<?php

namespace App\Http\Controllers\Deploy;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaykeDbRequest;
use App\Http\Requests\UpdatePaykeDbRequest;
use App\Models\DeployLog;
use App\Models\PaykeDb;
use App\Services\DeployLogService;
use App\Services\PaykeUserService;
use App\Services\PaykeResourceService;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function view_all(int $userId)
    {
        $service = new DeployLogService();
        $logs = $service->find_by_user_id($userId);

        $uService = new PaykeUserService();
        $user = $uService->find_by_id($userId);

        $rService = new PaykeResourceService();
        $resources = $rService->find_upper_version_to_array($user->PaykeResource);

        return view('deploy_log.index', ['user_id' => $userId, 'logs' => $logs, 'resources' => $resources]);
    }

    public function view_edit(int $id)
    {
        $service = new DeployLogService();
        $log = $service->find_by_id($id);
        return view('deploy_log.edit', ["log" => $log]);
    }

    public function post_edit(Request $request)
    {
        $id = $request->input('id');
        $service = new DeployLogService();
        $service->edit($id, $request->all());

        $log = $service->find_by_id($id);

        session()->flash('successTitle', 'メモを更新しました。');
        return redirect()->route('deploy_log.index', ["userId" => $log->user_id]);
    }
}
