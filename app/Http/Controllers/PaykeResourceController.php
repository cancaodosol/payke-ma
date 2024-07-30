<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaykeResource\CreateRequest;
use App\Services\PaykeResourceService;
use App\Services\DeploySettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaykeResourceController extends Controller
{
    public function view_all(Request $request)
    {
        $service = new PaykeResourceService();
        $resources = $service->find_all();
        $payke_resource_ids = [];
        $dService = new DeploySettingService();
        $units = $dService->find_units_all();
        foreach ($units as $unit) {
            if($unit->get_value("payke_resource_id")){
                $payke_resource_ids[] = $unit->get_value("payke_resource_id");
            }
        }
        return view('payke_resource.index', ['paykes' => $resources, 'setting_payke_resource_ids' => $payke_resource_ids]);
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
        $memo = $request->memo();
        $service->save($payke_zip_file_path, $memo);

        session()->flash('successTitle', 'Paykeバージョンを追加しました。');
        return redirect()->route('payke_resource.index');
    }

    public function view_edit(int $id)
    {
        $service = new PaykeResourceService();
        $resource = $service->find_by_id($id);
        return view('payke_resource.edit', ["resource" => $resource]);
    }

    public function post_edit(Request $request)
    {
        $id = $request->input('id');
        $service = new PaykeResourceService();
        $service->edit($id, $request->all());

        session()->flash('successTitle', '成功！');
        session()->flash('successMessage', "Paykeバージョン情報を更新しました。");
        return redirect()->route('payke_resource.index');
    }

    public function download(int $id)
    {
        $service = new PaykeResourceService();
        $resource = $service->find_by_id($id);
        $filePath = str_replace("storage/app/", "", $resource->payke_zip_file_path);
        $fileName = $resource->payke_zip_name.".zip";
        $headers = ['Content-Type' => Storage::mimeType($filePath)];
        return Storage::download($filePath, $fileName, $headers);
    }
}