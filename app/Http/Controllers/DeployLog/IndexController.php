<?php

namespace App\Http\Controllers\DeployLog;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaykeDbRequest;
use App\Http\Requests\UpdatePaykeDbRequest;
use App\Models\DeployLog;
use App\Models\PaykeDb;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(int $userId)
    {
        $logs = DeployLog::where('user_id', $userId)->get();
        return view('deploy_log.index', ['logs' => $logs]);
    }
}
