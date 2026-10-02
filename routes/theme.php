<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;

Route::get('/', function () {
    $default = \App\Models\Language::getDefault();
    return redirect()->to('/' . $default);
});

Route::group(['prefix' => '{locale}', 'middleware' => 'setlocale'], function () {

    Route::get('/', [FrontendController::class, 'home'])->name('home');
    Route::get('/sculptures', [FrontendController::class, 'sculptures'])->name('sculptures.index');
    Route::get('/sculptures/{slug}', [FrontendController::class, 'sculptureShow'])->name('sculpture.show');
    Route::get('/about', [FrontendController::class, 'about'])->name('about');
    Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');

});

Route::get('/lang/{code}', [FrontendController::class, 'switchLang'])->name('lang.switch');
