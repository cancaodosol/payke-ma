<?php

namespace App\Http\Controllers\PaykeResource;

use App\Http\Controllers\Controller;
use App\Models\PaykeResource;
use App\Services\PaykeResourceService;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $service = new PaykeResourceService();
        $resources = $service->find_all();
        dd($resources);
        return view('payke_resource.index', ['resources' => $resources]);
    }
}
