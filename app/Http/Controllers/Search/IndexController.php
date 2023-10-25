<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $searchWord = (string) $request->input('searchWord');
        switch($searchWord)
        {
            case ':newuser' :
                return redirect()->route('payke_user.create');
            case ':hosts' :
                return redirect()->route('payke_host.index');
        }
        dd($searchWord);
    }
}
