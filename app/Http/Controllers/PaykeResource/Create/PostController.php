<?php

namespace App\Http\Controllers\PaykeResource\Create;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaykeResource\CreateRequest;
use App\Services\PaykeResourceService;

class PostController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(CreateRequest $request)
    {
        // アップロードされたファイル名を取得
        $file_name = $request->paykeZip()->getClientOriginalName();

        // 取得したファイル名で保存
        // storage/app/payke_resources/zips/
        $request->paykeZip()->storeAs('payke_resources/zips', $file_name);

        // データベースへ保存
        $service = new PaykeResourceService();
        $payke_zip_file_path = "storage/app/payke_resources/zips/{$file_name}";
        $service->save($payke_zip_file_path);

        return redirect()->route('payke_user.index');
    }
}