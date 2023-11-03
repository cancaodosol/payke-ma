<?php

namespace App\Http\Controllers\PaykeUser\Create;

use App\Http\Controllers\Controller;
use App\Models\PaykeDb;
use App\Models\PaykeHost;
use App\Models\PaykeResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $hosts = PaykeHost::with(['PaykeDbs' => function($db) {
            $db->orderBy('db_database','asc');
        }])->get();
        $resources = PaykeResource::orderByRaw('version_x DESC, version_y DESC, version_z DESC, payke_name DESC')->get();
        $resources = array_map(function($x){
            return ["id" => $x['id'], "name" => $x['payke_name']];
        }, $resources->toarray());

        // MEMO: HostとDBは、一連なので、同じ項目として扱う。
        //       登録IDは、登録時に分裂させる。
        $host_dbs = [];
        foreach ($hosts as $host)
        {
            foreach($host->PaykeDbs as $db)
            {
                if($db->status != PaykeDb::STATUS__READY) continue;
                array_push($host_dbs, ["id" => "{$host->id}_{$db->id}", "name" => "{$host->name} / {$db->db_database}"]);
            }
        }
        return view('payke_user.create', ["host_dbs" => $host_dbs, "resources" => $resources]);
    }
}
