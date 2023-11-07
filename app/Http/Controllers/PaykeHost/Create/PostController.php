<?php

namespace App\Http\Controllers\PaykeHost\Create;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return redirect()->route('payke_user.index');
    }
}