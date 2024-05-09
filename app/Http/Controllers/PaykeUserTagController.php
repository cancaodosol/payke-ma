<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PaykeUserTag;
use Illuminate\Http\Request;

use App\Http\Requests\PaykeUserTag\TagsRequest;
use App\Models\PaykeUser;
use App\Models\PaykeEcOrder;
use App\Services\DeployService;
use App\Services\PaykeDbService;
use App\Services\PaykeHostService;
use App\Services\PaykeResourceService;
use App\Services\PaykeUserService;
use App\Services\DeployLogService;
use App\Jobs\DeployJob;
use Carbon\Carbon;

class PaykeUserTagController extends Controller
{
    public function view_all(Request $request)
    {
        $tags = PaykeUserTag::orderByRaw("order_no ASC")->get();
        return view('payke_user_tag.index', ['tags' => $tags]);
    }

    public function post_edit(TagsRequest $request)
    {
        $tags = $request->to_payke_user_tags();
        array_multisort(array_column($tags, "order_no"), SORT_ASC, $tags);

        for($i = 0; $i < count($tags); $i++){
            $tag = $tags[$i];
            $tag->order_no = 10 * ($i + 1);
            if(!$tag->id){
                $tag->save();
                continue;
            }
            $nowtag = PaykeUserTag::where("id", $tag->id)->first();
            $nowtag->update($tag->to_array());
        }
        session()->flash('successTitle', 'タグを更新しました！');
        session()->flash('successMessage', "");
        return redirect()->route('payke_user_tags.index');
    }
}