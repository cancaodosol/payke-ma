<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;

use App\Models\DeployLog;
use App\Services\DeployLogService;
use App\Services\PaykeUserService;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $uService = new PaykeUserService();
        $tags = $uService->get_tags();
        $users = $uService->find_all();
        $users_summary = [];
        foreach ($tags as $tag) {
            $users_summary[$tag->id] = [
                "tag_id" => $tag->id,
                "tag_name" => $tag->name,
                "tag_color" => $tag->color(),
                "is_hidden_tag" => $tag->is_hidden,
                "user_statuses" => [],
            ];
        }
        $users_summary[1000] = [
            "tag_id" => null,
            "tag_name" => "未設定",
            "tag_color" => "grey",
            "is_hidden_tag" => false,
            "user_statuses" => [],
        ];

        foreach ($users as $user) {
            $tag_index = $user->tag_id != null ? $user->tag_id : 1000;
            if(empty($users_summary[$tag_index]["user_statuses"][$user->status])){
                $users_summary[$tag_index]["user_statuses"][$user->status] = [
                    "status_id" => $user->status,
                    "status_name" => $user->status_name(),
                    "status_color" => $user->status_color(),
                    "users" => [ $user ],
                    "user_ids" => "{$user->id}"
                ];
                continue;
            }
            $users_summary[$tag_index]["user_statuses"][$user->status]["users"][] = $user;
            $users_summary[$tag_index]["user_statuses"][$user->status]["user_ids"] .= ",".$user->id;
        }

        $deployLogs = DeployLog::where([['created_at', '>=', new DateTime('-3 days')]])->orderBy('created_at', 'desc')->paginate(20);
        return view('home',['logs' => $deployLogs, 'users_summary' => $users_summary]);
    }
}