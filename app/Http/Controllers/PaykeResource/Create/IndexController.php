<?php

namespace App\Http\Controllers\PaykeResource\Create;

use App\Http\Controllers\Controller;
use App\Models\PaykeUser;
use App\Services\DeployService;
use App\Services\PaykeResourceService;
use App\Services\PaykeUserService;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return view('payke_resource.create');
    }
}