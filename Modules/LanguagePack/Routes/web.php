<?php

use Illuminate\Support\Facades\Route;
use Modules\LanguagePack\Http\Controllers\LanguagePackController;

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


// Admin routes
Route::group(['middleware' => 'auth', 'prefix' => 'account'], function () {

    Route::group(
        ['prefix' => 'settings'],
        function () {
            Route::post('language-pack/publish-all', [LanguagePackController::class, 'publishAll'])->name('language-pack.publish-all');
            Route::post('language-pack/publish', [LanguagePackController::class, 'publish'])->name('language-pack.publish');
        }
    );

});
