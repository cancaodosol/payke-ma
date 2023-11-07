<?php

namespace App\Http\Controllers\PaykeDb\Create;

use App\Http\Controllers\Controller;
use App\Services\PaykeHostService;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $hostService = new PaykeHostService();
        $hosts = $hostService->find_all_to_array();
        return view('payke_db.create', ["hosts" => $hosts]);
    }
}