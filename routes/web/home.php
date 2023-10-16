<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
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
use \Illuminate\Support\Facades\Gate;

Route::get('/', function () {
//    if(Gate::allows('edit-user')){

//    return \Morilog\Jalali\Jalalian::now()->subDays(2);

//    return view('welcome');

//    }
//    return 'no';
    auth()->loginUsingId(1);
});


Auth::routes(['verify'=>true]);
Route::get('/auth/google', [\App\Http\Controllers\Auth\GoogleAuthController::class , 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [\App\Http\Controllers\Auth\GoogleAuthController::class , 'callback']);
Route::get('/auth/token', [\App\Http\Controllers\Auth\AuthTokenController::class , 'getToken'])->name('2fa.token');
Route::post('/auth/token', [\App\Http\Controllers\Auth\AuthTokenController::class , 'postToken']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('profile')->middleware('auth')->group(function (){
    Route::get('/',[App\Http\Controllers\Profile\IndexController::class, 'index'])->name('profile');
    Route::get('twofactor',[App\Http\Controllers\Profile\TwoFactorAuthController::class, 'manageTwoFactor'])->name('profile.2fa.manage');
    Route::post('twofactor',[App\Http\Controllers\Profile\TwoFactorAuthController::class, 'postManageTwoFactor']);

    Route::get('twofacto/phone', [App\Http\Controllers\Profile\TokenAuthController::class, 'getPhoneVerify'])->name('profile.2fa.phone');
    Route::post('twofacto/phone', [App\Http\Controllers\Profile\TokenAuthController::class, 'postPhoneVerify']);
});

Route::get('products', [\App\Http\Controllers\ProductController::class, 'index']);
Route::get('products/{product}', [\App\Http\Controllers\ProductController::class, 'single']);
Route::post('comments', [\App\Http\Controllers\HomeController::class, 'comment'])->name('send.comment');
