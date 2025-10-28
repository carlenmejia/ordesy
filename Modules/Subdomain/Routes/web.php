<?php


use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Modules\Subdomain\Http\Middleware\SubdomainCheck;
use Modules\Subdomain\Http\Controllers\SubdomainController;
use App\Http\Controllers\HomeController;

use App\Http\Middleware\SuperAdmin;

Route::group(['middleware' => ['web', SubdomainCheck::class]], function () {

    Route::get('/', [SubdomainController::class, 'shopIndex'])->name('shop_restaurant');
    Route::get('/restaurant/{hash}', [SubdomainController::class, 'redirectHash'])->name('shop_restaurant');
    Route::get('/quick-login/{hash}', [SubdomainController::class, 'quickLoginSubdomain'])->name('quick_login');

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login')->middleware('guest');
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');


    Route::get('forgot-restaurant', 'SubdomainController@forgotRestaurant')->name('front.forgot-restaurant')->middleware('guest');
    Route::post('forgot-restaurant', 'SubdomainController@submitForgotRestaurant')->name('front.submit-forgot-password')->middleware('guest');
    Route::get('signin', [SubdomainController::class, 'workspace'])->name('front.workspace');

    Route::get('/restaurant-signup', [HomeController::class, 'signup'])->name('restaurant_signup');

    Route::get('/super-admin-login', [AuthenticatedSessionController::class, 'create'])->middleware('guest');
    Route::post('/super-admin-login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');
});




Route::middleware(['auth', config('jetstream.auth_session'), 'verified', SuperAdmin::class])->group(function () {
    Route::post('check-domain', [SubdomainController::class, 'checkDomain'])->name('front.check-domain');
});
