<?php

use App\Http\Controllers\Admin\Containers\Container;
use App\Http\Controllers\Admin\Dashboard\Dashboard;
use App\Http\Controllers\Admin\Profile\Profile;
use App\Http\Controllers\Admin\Requests\IndexRequest;
use App\Http\Controllers\Admin\Roles\IndexRole;
use App\Http\Controllers\Admin\Roles\StoreRole;
use App\Http\Controllers\Admin\Users\IndexUser;
use App\Http\Controllers\Admin\Users\StoreUser;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware(['auth'])->get('/',Dashboard::class);

Route::middleware(['auth'])->prefix('container')->group(function() {
    Route::get('/',Dashboard::class)->name('dashboard');
    Route::get('/requests', IndexRequest::class)->name('requests');

    Route::get('/users',IndexUser::class)->name('user');
    Route::get('/users/{action}/{id?}',StoreUser::class)->name('store.user');

    Route::get('/roles',IndexRole::class)->name('role');
    Route::get('/roles/{action}/{id?}',StoreRole::class)->name('store.role');

    Route::get('/container',Container::class)->name('container');
    Route::get('/profile', Profile::class)->name('profile');

    Route::get('/categories',\App\Http\Controllers\Admin\Categories\IndexCategory::class)->name('categories');
    Route::get('/categories/{action}/{id?}',\App\Http\Controllers\Admin\Categories\StoreCategory::class)->name('category');

    Route::get('/carts',\App\Http\Controllers\Admin\Carts\IndexCart::class)->name('carts');
    Route::get('/carts/{action}/{id?}',\App\Http\Controllers\Admin\Carts\StoreCart::class)->name('cart');

    Route::get('/panels',\App\Http\Controllers\Admin\Panels\IndexPanel::class)->name('panels');
    Route::get('/panels/{action}/{id?}',\App\Http\Controllers\Admin\Panels\StorePanel::class)->name('panel');

    Route::get('/currencies',\App\Http\Controllers\Admin\Currencies\IndexCurrency::class)->name('currencies');
    Route::get('/currencies/{action}/{id?}',\App\Http\Controllers\Admin\Currencies\StoreCurrency::class)->name('currency');


});

Route::group(['prefix' => 'file-manager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});


Route::middleware('guest')->get('auth',App\Http\Controllers\Site\Auth\Auth::class)->name('auth');

Route::get('/logout', function (){
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('auth');
})->name('logout');
