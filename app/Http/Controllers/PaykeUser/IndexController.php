<?php

namespace App\Http\Controllers\PaykeUser;

use App\Http\Controllers\Controller;
use App\Models\PaykeUser;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $users = PaykeUser::all();
        dd($users);
        return view('payke_user.index', ['users' => $users]);
    }
}
