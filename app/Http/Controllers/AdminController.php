<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaykeDb\CreateRequest;
use App\Services\UserService;
use App\Services\PaykeDbService;
use App\Services\PaykeHostService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function view_all(Request $request)
    {
        $service = new UserService();
        $users = $service->find_admin_users();
        return view('admin.index', ['users' => $users]);
    }

    public function post_edit_password(Request $request)
    {
        $id = $request->input('id');
        $password = $request->input('password');
        $service = new UserService();
        $service->edit_password($id, $password);

        session()->flash('successTitle', 'パスワードを更新しました。');
        return redirect()->route('admin.index');
    }
}