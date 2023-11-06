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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::get('/sample', [\App\Http\Controllers\Sample\IndexController::class, 'show']);
Route::get('/sample/{id}', [\App\Http\Controllers\Sample\IndexController::class, 'showId']);

Route::get('/tweet', \App\Http\Controllers\Tweet\IndexController::class)
    ->name('tweet.index');

Route::get('/payke_host', \App\Http\Controllers\PaykeHost\IndexController::class)
    ->name('payke_host.index');

Route::get('/payke_db', \App\Http\Controllers\PaykeDb\IndexController::class)
    ->name('payke_db.index');

Route::get('/payke_resource', \App\Http\Controllers\PaykeResource\IndexController::class)
    ->name('payke_resource.index');

Route::get('/payke_resource/create', \App\Http\Controllers\PaykeResource\Create\IndexController::class)
    ->name('payke_resource.creaete');

Route::post('/payke_resource/create', \App\Http\Controllers\PaykeResource\Create\PostController::class)
    ->name('payke_resource.create.post');

Route::get('/payke_user', \App\Http\Controllers\PaykeUser\IndexController::class)
    ->name('payke_user.index');

Route::get('/payke_user/create', \App\Http\Controllers\PaykeUser\Create\IndexController::class)
    ->name('payke_user.create');

Route::post('/payke_user/create/post', \App\Http\Controllers\PaykeUser\Create\PostController::class)
    ->name('payke_user.create.post');

Route::post('/payke_user/version/up', \App\Http\Controllers\PaykeUser\Version\UpController::class)
    ->name('payke_user.version.up');

Route::get('/deploy_log/{userId}', \App\Http\Controllers\DeployLog\IndexController::class)
    ->name('deploy_log.index');

Route::post('/search', \App\Http\Controllers\Search\IndexController::class)
    ->name('search.index');

Route::middleware('auth')->group(
    function()
    {
        Route::post('/tweet/create', \App\Http\Controllers\Tweet\CreateController::class)
            ->name('tweet.create');
        Route::get('/tweet/update/{tweetId}', \App\Http\Controllers\Tweet\Update\IndexController::class)
            ->name('tweet.update.index');
        Route::put('/tweet/update/{tweetId}', \App\Http\Controllers\Tweet\Update\PutController::class)
            ->name('tweet.update.put');
        Route::delete('/tweet/delete/{tweetId}', \App\Http\Controllers\Tweet\DeleteController::class)
            ->name('tweet.delete');
    }
);