<?php

namespace App\Http\Controllers\PaykeDb\Edit;

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
        $id = $request->input('id');
        $service = new PaykeDbService();
        $service->edit($id, $request->all());
        return redirect()->route('payke_host.index');
    }
}