<?php

namespace App\Http\Controllers\PaykeHost;

use App\Http\Controllers\Controller;
use App\Models\PaykeHost;
use App\Services\PaykeHostService;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $service = new PaykeHostService();
        $hosts = $service->find_all();
        return view('payke_host.index', ['hosts' => $hosts]);
    }
}
