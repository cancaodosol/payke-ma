<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaykeResource\CreateRequest;
use App\Services\PaykeResourceService;
use Illuminate\Http\Request;

class PaykeResourceController extends Controller
{
    public function view_all(Request $request)
    {
        $service = new PaykeResourceService();
        $resources = $service->find_all();
        return view('payke_resource.index', ['paykes' => $resources]);
    }

    public function post_add(CreateRequest $request)
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

    public function view_edit(int $id)
    {
        return;
    }

    public function post_edit(Request $request)
    {
        return;
    }
}