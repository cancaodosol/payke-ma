<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\DeployLog;
use App\Services\DeployLogService;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $deployLogs = DeployLog::orderBy('created_at', 'desc')->paginate(20);
        return view('home',['logs' => $deployLogs]);
    }

}