<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaykeHost\CreateRequest;
use App\Services\PaykeHostService;
use Illuminate\Http\Request;

class PaykeHostController extends Controller
{
    public string $root_dir;
    public function __construct()
    {
        $this->root_dir = dirname(__FILE__)."/../../../";
    }

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

    public function post_add(CreateRequest $request)
    {
        $host = $request->to_payke_host();

        // アップロードされたファイル名を取得
        $file_name = $request->identityFile()->getClientOriginalName();

        // 取得したファイル名で保存
        $request->identityFile()->storeAs('.ssh', $file_name);

        // データベースへ保存
        $service = new PaykeHostService();
        $host->identity_file = "storage/app/.ssh/{$file_name}";

        // 権限を600に。
        chmod($this->root_dir.$host->identity_file, 0600);

        $service->add($host);

        session()->flash('successTitle', '成功！');
        session()->flash('successMessage', "サーバー情報を新規登録しました。");
        return redirect()->route('payke_host.index');
    }

    public function view_edit(int $id)
    {
        $service = new PaykeHostService();
        $statuses = $service->get_statuses();
        $host = $service->find_by_id($id);
        return view('payke_host.edit', ["host" => $host, "statuses" => $statuses]);
    }

    public function post_edit(CreateRequest $request)
    {
        // データベースへ保存
        $service = new PaykeHostService();
        $id = $request->input('id');
        $data = $request->all();

        // 公開鍵の変更があったら、更新する。
        if($request->identityFile___edit())
        {
            // アップロードされたファイル名を取得
            $file_name = $request->identityFile___edit()->getClientOriginalName();
    
            // 取得したファイル名で保存
            $request->identityFile___edit()->storeAs('.ssh', $file_name);

            $data['identity_file'] = "storage/app/.ssh/{$file_name}";

            // 権限を600に。
            chmod($this->root_dir.$data['identity_file'], 0600);
        }

        $service->edit($id, $data);

        session()->flash('successTitle', '成功！');
        session()->flash('successMessage', "サーバー情報を更新しました。");
        return redirect()->route('payke_host.index');
    }
}