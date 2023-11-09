<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\PaykeDbService;
use App\Services\PaykeHostService;
use Illuminate\Http\Request;

class PaykeDbController extends Controller
{
    public function view_all(Request $request)
    {
        $service = new PaykeDbService();
        $dbs = $service->find_all();
        dd($dbs);
        return view('payke_db.index', ['dbs' => $dbs]);
    }

    public function view_add(Request $request)
    {
        $hostService = new PaykeHostService();
        $hosts = $hostService->find_all_to_array();
        return view('payke_db.create', ["hosts" => $hosts]);
    }

    public function post_add(Request $request)
    {
        $db = $request->to_payke_db();

        $service = new PaykeDbService();
        $service->add($db);

        return redirect()->route('payke_host.index');
    }

    public function view_edit(int $id)
    {
        $hostService = new PaykeHostService();
        $hosts = $hostService->find_all_to_array();
        $dbService = new PaykeDbService();
        $statuses = $dbService->get_statuses($id);
        $db = $dbService->find_by_id($id);
        return view('payke_db.edit', ["hosts" => $hosts, "statuses" => $statuses, "db" => $db]);
    }

    public function post_edit(Request $request)
    {
        $id = $request->input('id');
        $service = new PaykeDbService();
        $service->edit($id, $request->all());
        return redirect()->route('payke_host.index');
    }
}