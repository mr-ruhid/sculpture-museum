<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/wikis', [AdminController::class, 'wikis'])->name('wikis.index');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::get('/about', [AdminController::class, 'about'])->name('about');
});

Route::post('/logout', function () {
    auth()->logout();
    return redirect('/');
})->name('logout');
