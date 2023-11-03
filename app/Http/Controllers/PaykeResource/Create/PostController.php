<?php

namespace App\Http\Controllers\PaykeResource\Create;

use App\Http\Controllers\Controller;
use App\Models\PaykeUser;
use App\Services\DeployService;
use App\Services\PaykeResourceService;
use App\Services\PaykeUserService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // アップロードされたファイル名を取得
        $file_name = $request->file('file-upload')->getClientOriginalName();

        // 取得したファイル名で保存
        // storage/app/payke_resources/zips/
        $request->file('file-upload')->storeAs('payke_resources/zips', $file_name);

        // データベースへ保存
        $service = new PaykeResourceService();
        $payke_zip_file_path = "storage/app/payke_resources/zips/{$file_name}";
        $service->save($payke_zip_file_path);

        return redirect('/');
    }
}