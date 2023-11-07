<?php

namespace App\Http\Controllers\PaykeDb\Edit;

use App\Http\Controllers\Controller;
use App\Services\PaykeDbService;
use App\Services\PaykeHostService;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(int $id)
    {
        $hostService = new PaykeHostService();
        $hosts = $hostService->find_all_to_array();
        $dbService = new PaykeDbService();
        $db = $dbService->find_by_id($id);
        return view('payke_db.edit', ["hosts" => $hosts, "db" => $db]);
    }
}