<?php

namespace App\Http\Controllers\PaykeUser\Create;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaykeUser\CreateRequest;
use App\Models\PaykeUser;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(CreateRequest $request)
    {
        dd([
            "payke_host_db" => $request->input('payke_host_db'),
            "paykeHostId" => $request->paykeHostId(),
            "paykeDbId" => $request->paykeDbId()
        ]);
        $user = new PaykeUser();
        return redirect()->route('tweet.index');
    }
}