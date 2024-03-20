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

Route::get('/dashboard', [App\Http\Controllers\ProfileController::class ,'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/payke/ec2ma', [\App\Http\Controllers\PaykeController::class, 'connect_paykeec_to_ma'])
    ->name('payke.ec2ma');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
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
        
        Route::get('/deploy_log/{userId}', [\App\Http\Controllers\DeployLog\IndexController::class, 'view_all'])
            ->name('deploy_log.index');

        Route::get('/deploy_log/edit/{id}', [\App\Http\Controllers\DeployLog\IndexController::class, 'view_edit'])
            ->name('deploy_log.edit');
        
        Route::post('/deploy_log/edit/post', [\App\Http\Controllers\DeployLog\IndexController::class, 'post_edit'])
            ->name('deploy_log.edit.post');
        
        Route::post('/search', \App\Http\Controllers\Search\IndexController::class)
            ->name('search.index');
        
        Route::get('/api/jobqueue', [\App\Http\Controllers\PaykeController::class, 'api_get_jobqueue'])
            ->name('api.jobqueue.index');
    }
);