<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Services\PaykeUserService;
use App\Services\DeployLogService;
use App\Models\PaykeUser;

class ProfileController extends Controller
{
    /**
     * Display the user's Profile.
     */
    public function index(Request $request): View
    {
        $pUser = count($request->user()->PaykeUsers) > 0 ? $request->user()->PaykeUsers[0] : null;

        if($pUser == null) {
            return view('profile.index', [
                'user' => $request->user(),
            ]);
        }

        if($pUser->is_before_setting())
        {
            return view('profile.init', [
                'user' => $request->user(),
            ]);
        }
        return view('profile.index', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
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
     * Update the user's payke ec name.
     */
    public function update_app_name(Request $request)
    {
        $pUser = $request->user()->PaykeUsers[0];
        $service = new PaykeUserService();

        $id = $request->input("id");
        $new_app_name = $request->input("user_app_name");

        if($service->exists_same_name($pUser->payke_host_id, $new_app_name))
        {
            session()->flash('errorTitle', '入力内容に問題があります。');
            session()->flash('errorMessage', "公開アプリ名「{$new_app_name}」は使用できません。別の名前でご登録ください。");
            return view('profile.init', ["user" => $request->user()]);
        }

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
