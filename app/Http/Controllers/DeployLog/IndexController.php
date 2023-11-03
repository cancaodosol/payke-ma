<?php

namespace App\Http\Controllers\DeployLog;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaykeDbRequest;
use App\Http\Requests\UpdatePaykeDbRequest;
use App\Models\DeployLog;
use App\Models\PaykeDb;
use App\Services\DeployLogService;
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
        return view('deploy_log.index', ['logs' => $logs]);
    }
}
