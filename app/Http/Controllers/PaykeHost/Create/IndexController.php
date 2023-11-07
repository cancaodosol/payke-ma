<?php

namespace App\Http\Controllers\PaykeHost\Create;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return view('payke_host.create');
    }
}