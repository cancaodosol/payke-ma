<?php

namespace App\Http\Controllers\PaykeDb;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaykeDbRequest;
use App\Http\Requests\UpdatePaykeDbRequest;
use App\Models\PaykeDb;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $dbs = PaykeDb::all();
        dd($dbs);
        return view('payke_db.index', ['dbs' => $dbs]);
    }
}
