<?php

namespace App\Http\Controllers\PaykeDb\Create;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaykeDb\CreateRequest;
use App\Services\PaykeDbService;

class PostController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(CreateRequest $request)
    {
        $db = $request->to_payke_db();

        $service = new PaykeDbService();
        $service->add($db);

        return redirect()->route('payke_host.index');
    }
}