<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Models\DeployLog;
use App\Models\PaykeUser;
use App\Services\DeployService;
use Illuminate\Http\Request;

use function Laravel\Prompts\search;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $searchWords = explode(" ", (string) $request->input('searchWord'));
        switch($searchWords[0])
        {
            case ':newuser' :
                return redirect()->route('payke_user.create');
            case ':hosts' :
                return redirect()->route('payke_host.index');
            case ':logs' :
                $deployLog = DeployLog::all()[0];
                dd($deployLog->getDiffTime());
            case ':logs_view' :
                $deployLogs = DeployLog::all()->sortByDesc('created_at');
                return view('deploy_log.index', ["logs" => $deployLogs]);
            case ':unlock' :
                $user = PaykeUser::where('id', $searchWords[1])->firstOrFail();
                $deploy = new DeployService();
                $logs = [];
                $deploy->unlock($user->PaykeHost, $user, $user->PaykeDb, $user->PaykeResource, $logs);
                dd($logs);
            case ':test' :
                return view('common.result', ["title" => "成功！", "message" => "Payke v3.23.1 のデプロイに成功しました！",
                    "info" => ["task deploy:info",
                        "[payke_release] info deploying HEAD",
                        " task deploy:setup",
                        " task deploy:lock",
                        " task deploy:release:db_backup"]]);
        }
        dd($searchWords);
    }
}
