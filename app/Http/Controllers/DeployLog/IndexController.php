<?php

namespace App\Http\Controllers\DeployLog;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaykeDbRequest;
use App\Http\Requests\UpdatePaykeDbRequest;
use App\Models\DeployLog;
use App\Models\PaykeDb;
use App\Services\DeployLogService;
use App\Services\PaykeUserService;
use App\Services\PaykeResourceService;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(int $userId)
    {
        $service = new DeployLogService();
        $logs = $service->find_by_user_id($userId);

        $uService = new PaykeUserService();
        $user = $uService->find_by_id($userId);

        $rService = new PaykeResourceService();
        $resources = $rService->find_upper_version_to_array($user->PaykeResource);

        return view('deploy_log.index', ['user_id' => $userId, 'logs' => $logs, 'resources' => $resources]);
    }
}
