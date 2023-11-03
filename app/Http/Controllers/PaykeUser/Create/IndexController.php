<?php

namespace App\Http\Controllers\PaykeUser\Create;

use App\Http\Controllers\Controller;
use App\Models\PaykeDb;
use App\Models\PaykeHost;
use App\Models\PaykeResource;
use App\Services\PaykeDbService;
use App\Services\PaykeResourceService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $resService = new PaykeResourceService();
        $resources = $resService->find_all_to_array();

        // MEMO: HostとDBは、一連なので、同じ項目として扱う。
        //       登録IDは、登録時に分裂させる。
        $dbService = new PaykeDbService();
        $host_dbs = $dbService->find_ready_host_dbs();

        return view('payke_user.create', ["host_dbs" => $host_dbs, "resources" => $resources]);
    }
}
