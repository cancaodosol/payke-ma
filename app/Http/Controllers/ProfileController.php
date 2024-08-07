<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Contracts\Mail\Mailer;

use App\Services\UserService;
use App\Services\PaykeUserService;
use App\Services\PaykeApiService;
use App\Services\DeployLogService;
use App\Services\DeploySettingService;
use App\Services\MailService;
use App\Models\PaykeUser;

class ProfileController extends Controller
{
    /**
     * Display the user's Profile.
     */
    public function index(Request $request): View
    {
        $pUser = count($request->user()->PaykeUsers) > 0 ? $request->user()->PaykeUsers[0] : null;
        $user = (new UserService())->find_by_id($request->user()->id);

        // 稼働中のPayke環境がない場合
        if($pUser == null) {
            return view('profile.index', [
                'user' => $user,
            ]);
        }

        // 稼働中のPayke環境の初期設定が終わってない場合
        if($pUser->is_before_setting())
        {
            return view('profile.init', [
                'user' => $user,
            ]);
        }

        $service = new DeploySettingService();
        $units = $service->find_units_all();
        foreach ($user->PaykeUsers as $paykeUser) {
            $paykeUser->set_deploy_setting_name(($paykeUser->is_unused_or_delete() ? "停止" : "なし"));
            foreach ($units as $unit) {
                if($unit->get_value("is_plan") && $unit->no == $paykeUser->deploy_setting_no){
                    $paykeUser->set_deploy_setting_name($unit->get_value("setting_title"));
                }
            }
        }

        return view('profile.index', [
            'user' => $user
        ]);
    }

    /**
     * Show Payke Plan.
     */
    public function plan_view(Request $request): View
    {
        $pUser = null;
        $user = (new UserService())->find_by_id($request->user()->id);
        foreach ($user->PaykeUsers as $paykeUser) {
            if($paykeUser->uuid == $request->payke_user_uuid){
                $pUser = $paykeUser;
                break;
            }
        }

        if($pUser == null){
            return redirect()->route("profile.index");
        }

        $service = new DeploySettingService();
        $allunits = $service->find_units_all();
        $units = [];
        foreach ($allunits as $unit) {
            if($unit->get_value("is_plan")){
                $units[] = $unit;
            }
        }

        return view('profile.plan_list', [
            'user' => $user,
            'pUser' => $pUser,
            'units' => $units
        ]);
    }

    /**
     * Show Payke Plan Explain.
     */
    public function plan_explain_view(Request $request): View
    {
        $pUser = null;
        $user = (new UserService())->find_by_id($request->user()->id);
        foreach ($user->PaykeUsers as $paykeUser) {
            if($paykeUser->uuid == $request->payke_user_uuid){
                $pUser = $paykeUser;
                break;
            }
        }

        if($pUser == null){
            return redirect()->route("profile.index");
        }

        $service = new DeploySettingService();
        $unit = $service->find_by_no($request->plan_no);

        return view('profile.plan_explain', [
            'user' => $user,
            'pUser' => $pUser,
            'unit' => $unit
        ]);
    }

    /**
     * Change Payke Plan.
     */
    public function edit_plan(Request $request): View
    {
        $user = (new UserService())->find_by_id($request->user()->id);
        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = (new UserService())->find_by_id($request->user()->id);
        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Display the plan cancel comfirm.
     */
    public function cancel_view(Request $request): View
    {
        $pUser = null;
        $user = (new UserService())->find_by_id($request->user()->id);
        foreach ($user->PaykeUsers as $paykeUser) {
            if($paykeUser->uuid == $request->payke_user_uuid){
                $pUser = $paykeUser;
                break;
            }
        }

        if($pUser == null){
            return redirect()->route("profile.index");
        }

        return view('profile.cancel', [
            'pUser' => $pUser,
        ]);
    }

    /**
     * Confirm the user's plan.
     */
    public function cancel_confirm(Request $request, Mailer $mailer): View
    {
        $pUser = null;
        $user = (new UserService())->find_by_id($request->user()->id);
        foreach ($user->PaykeUsers as $paykeUser) {
            if($paykeUser->uuid == $request->payke_user_uuid){
                $pUser = $paykeUser;
                break;
            }
        }

        if($pUser == null){
            return redirect()->route("profile.index");
        }

        $ser = new PaykeApiService();
        $is_success = $ser->cancel_order($pUser->payke_order_id);

        $logSer = new DeployLogService();
        $message = "";
        if($is_success){
            $message = "Paykeを停止しました。ご利用ありがとうございました。";
            $logSer->write_warm_log($pUser, "利用終了", "利用者の操作によって注文データへキャンセル処理を行いました。");
        } else {
            $message = "システムエラーにより、終了処理が失敗しました。";
            $logSer->write_error_log($pUser, "利用終了失敗", "利用者の操作によって注文データへキャンセル処理を行いましたが、失敗しました。");
            $mService = new MailService($mailer);
            $title = "【お客様操作によるエラー】Payke 利用終了処理が失敗しました。";
            $message = "利用者の操作によって注文データへキャンセル処理を行いましたが、失敗しました。";
            $mService->send_to_admin($title, $message, [], $pUser->to_array_for_log());
        }

        return view('profile.cancel_confirm', ["message" => $message]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's payke name.
     */
    public function update_app_name(Request $request)
    {
        $id = $request->input("id");
        $new_app_name = $request->input("user_app_name");

        $service = new PaykeUserService();
        $pUser = $service->find_by_id($id);

        if($service->exists_same_name($pUser->payke_host_id, $new_app_name))
        {
            session()->flash('errorTitle', '入力内容に問題があります。');
            session()->flash('errorMessage', "公開アプリ名「{$new_app_name}」は使用できません。別の名前でご登録ください。");
            return redirect()->route("profile.index");
        }

        if($pUser->is_updating_now())
        {
            session()->flash('errorTitle', '現在アップデート中です。');
            session()->flash('errorMessage', "すでにアップデートが始まっております。しばらくお待ちください。");
            return redirect()->route("profile.index");
        }

        if($pUser->is_active())
        {
            return redirect()->route("profile.index");
        }

        $pUser->status = PaykeUser::STATUS__UPDATING_NOW;
        $service->edit($id, $pUser, false);

        $pUser->status = PaykeUser::STATUS__ACTIVE;
        $pUser->user_app_name = $new_app_name;
        $pUser->set_app_url($pUser->PaykeHost->hostname, $pUser->user_app_name);
        $service->edit($id, $pUser, false);

        return redirect()->route('profile.index');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
