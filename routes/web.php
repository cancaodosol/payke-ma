<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('payke_user.index');
})->name('home');

Route::get('/stopped', function () { return view('messages.app_is_stopped'); })
    ->name('app.stopped');

Route::post('/payke/ec2ma/{no}', [\App\Http\Controllers\PaykeController::class, 'connect_paykeec_to_ma'])
    ->name('payke.ec2ma');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update_app_name'])->name('profile.update_app_name');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'admin'])->group(
    function()
    {
        Route::get('/welcome', function () { return view('welcome'); });

        Route::get('/payke_host', [App\Http\Controllers\PaykeHostController::class ,'view_all'])
            ->name('payke_host.index');
        
        Route::get('/payke_host/create', [App\Http\Controllers\PaykeHostController::class ,'view_add'])
            ->name('payke_host.create');
        
        Route::post('/payke_host/create', [App\Http\Controllers\PaykeHostController::class ,'post_add'])
            ->name('payke_host.create.post');
        
        Route::get('/payke_host/e/{id}', [App\Http\Controllers\PaykeHostController::class ,'view_edit'])
            ->name('payke_host.edit');
        
        Route::post('/payke_host/edit', [App\Http\Controllers\PaykeHostController::class ,'post_edit'])
            ->name('payke_host.edit.post');
        
        Route::get('/payke_db', [\App\Http\Controllers\PaykeDbController::class, 'view_all'])
            ->name('payke_db.index');
        
        Route::get('/payke_db/create', [\App\Http\Controllers\PaykeDbController::class, 'view_add'])
            ->name('payke_db.create');
        
        Route::post('/payke_db/create', [\App\Http\Controllers\PaykeDbController::class, 'post_add'])
            ->name('payke_db.create.post');
        
        Route::post('/payke_db/create_many', [\App\Http\Controllers\PaykeDbController::class, 'post_add_many'])
            ->name('payke_db.create_many.post');
        
        Route::get('/payke_db/e/{id}', [\App\Http\Controllers\PaykeDbController::class, 'view_edit'])
            ->name('payke_db.edit');
        
        Route::post('/payke_db/e', [\App\Http\Controllers\PaykeDbController::class, 'post_edit'])
            ->name('payke_db.edit.post');
        
        Route::get('/payke_resource', [\App\Http\Controllers\PaykeResourceController::class, 'view_all'])
            ->name('payke_resource.index');
        
        Route::post('/payke_resource/create', [\App\Http\Controllers\PaykeResourceController::class, 'post_add'])
            ->name('payke_resource.create.post');
        
        Route::get('/payke_resource/e/{id}', [\App\Http\Controllers\PaykeResourceController::class, 'view_edit'])
            ->name('payke_resource.edit');
        
        Route::post('/payke_resource/e', [\App\Http\Controllers\PaykeResourceController::class, 'post_edit'])
            ->name('payke_resource.edit.post');
        
        Route::get('/payke_user', [\App\Http\Controllers\PaykeUserController::class, 'view_all'])
            ->name('payke_user.index');
        
        Route::get('/payke_user/s/{paykeId}', [\App\Http\Controllers\PaykeUserController::class, 'view_by_payke_id'])
            ->name('payke_user.index.paykeId');
        
        Route::get('/payke_user/p/{userId}', [\App\Http\Controllers\PaykeUserController::class, 'view_one'])
            ->name('payke_user.profile');
        
        Route::get('api/payke_user/p/{userId}', [\App\Http\Controllers\PaykeUserController::class, 'api_get_user'])
            ->name('api.payke_user.profile');

        Route::get('/payke_user/t/{tagId}', [\App\Http\Controllers\PaykeUserController::class, 'view_by_tag_id'])
            ->name('payke_user.index.tagId');
        
        Route::get('/payke_user/create', [\App\Http\Controllers\PaykeUserController::class, 'view_add'])
            ->name('payke_user.create');
        
        Route::post('/payke_user/create/post', [\App\Http\Controllers\PaykeUserController::class, 'post_add'])
            ->name('payke_user.create.post');
        
        Route::get('/payke_user/edit/{id}', [\App\Http\Controllers\PaykeUserController::class, 'view_edit'])
            ->name('payke_user.edit');
        
        Route::post('/payke_user/edit/post', [\App\Http\Controllers\PaykeUserController::class, 'post_edit'])
            ->name('payke_user.edit.post');
        
        Route::post('/payke_user/memo_edit/post', [\App\Http\Controllers\PaykeUserController::class, 'post_memo_edit'])
            ->name('payke_user.memo_edit.post');
        
        Route::post('/payke_user/version/up', [\App\Http\Controllers\PaykeController::class, 'post_edit_version'])
            ->name('payke_user.version.up');

        Route::get('/payke_user_tags', [\App\Http\Controllers\PaykeUserTagController::class, 'view_all'])
            ->name('payke_user_tags.index');
        
        Route::post('/payke_user_tags/edit', [\App\Http\Controllers\PaykeUserTagController::class, 'post_edit'])
            ->name('payke_user_tags.edit.post');
        
        Route::get('/deploy_log/{userId}', [\App\Http\Controllers\Deploy\LogController::class, 'view_all'])
            ->name('deploy_log.index');

            Route::get('/deploy_log/edit/{id}', [\App\Http\Controllers\Deploy\LogController::class, 'view_edit'])
            ->name('deploy_log.edit');
        
        Route::post('/deploy_log/edit/post', [\App\Http\Controllers\Deploy\LogController::class, 'post_edit'])
            ->name('deploy_log.edit.post');

        Route::get('/deploy_settings', [\App\Http\Controllers\Deploy\SettingController::class, 'view_all'])
            ->name('deploy_setting.index');

        Route::get('/deploy_setting/create', [\App\Http\Controllers\Deploy\SettingController::class, 'view_create'])
            ->name('deploy_setting.create');

        Route::get('/deploy_setting/edit/{no}', [\App\Http\Controllers\Deploy\SettingController::class, 'view_edit'])
            ->name('deploy_setting.edit');
        
        Route::post('/deploy_setting/edit/', [\App\Http\Controllers\Deploy\SettingController::class, 'post_edit'])
            ->name('deploy_setting.edit.post');

        Route::get('/release_notes', [\App\Http\Controllers\ReleaseNoteController::class, 'view_all'])
            ->name('release_note.index');

        Route::get('/admin', [\App\Http\Controllers\AdminController::class, 'view_all'])
            ->name('admin.index');

        Route::post('/admin/edit_password/post', [\App\Http\Controllers\AdminController::class, 'post_edit_password'])
            ->name('admin.edit.password');

        Route::post('/search', \App\Http\Controllers\Search\IndexController::class)
            ->name('search.index');
        
        Route::get('/api/jobqueue', [\App\Http\Controllers\PaykeController::class, 'api_get_jobqueue'])
            ->name('api.jobqueue.index');
    }
);