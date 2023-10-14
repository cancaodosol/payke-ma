<?php

namespace App\Http\Controllers\PaykeHost;

use App\Http\Controllers\Controller;
use App\Models\PaykeHost;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $hosts = PaykeHost::all();
        return view('payke_host.index', ['hosts' => $hosts]);
    }
}
