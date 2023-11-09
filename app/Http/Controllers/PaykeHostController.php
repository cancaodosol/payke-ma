<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\PaykeHostService;
use Illuminate\Http\Request;

class PaykeHostController extends Controller
{
    public function view_all(Request $request)
    {
        $service = new PaykeHostService();
        $hosts = $service->find_all();
        return view('payke_host.index', ['hosts' => $hosts]);
    }

    public function view_add(Request $request)
    {
        $hostService = new PaykeHostService();
        $hosts = $hostService->find_all_to_array();
        return view('payke_host.create');
    }

    public function post_add(Request $request)
    {
        return view('payke_host.index');
    }

    public function view_edit(int $id)
    {
        $service = new PaykeHostService();
        $statuses = $service->get_statuses();
        $host = $service->find_by_id($id);
        return view('payke_host.edit', ["host" => $host, "statuses" => $statuses]);
    }

    public function post_edit(Request $request)
    {
        return view('payke_host.index');
    }
}