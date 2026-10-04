<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\ImageController;

Route::get('/img/{path}', [ImageController::class, 'show'])
    ->where('path', '.*')
    ->name('image.show');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('/theme/{path}', function ($path) {
    $file = resource_path('views/theme/rjmuseum/' . $path);

    if (!file_exists($file) || !is_file($file)) {
        abort(404);
    }

    $mime = match (pathinfo($file, PATHINFO_EXTENSION)) {
        'css' => 'text/css',
        'js' => 'application/javascript',
        'svg' => 'image/svg+xml',
        'png' => 'image/png',
        'jpg', 'jpeg' => 'image/jpeg',
        'webp' => 'image/webp',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        default => 'application/octet-stream',
    };

    return response()->file($file, ['Content-Type' => $mime]);
})->where('path', '.*')->name('theme.asset');

Route::get('/', function () {
    $default = \App\Models\Language::getDefault();
    return redirect()->to('/' . $default);
});

Route::get('/q/{code}', [FrontendController::class, 'shortUrl'])
    ->where('code', '[a-zA-Z0-9\-]+')
    ->name('short-url.redirect');

Route::group(['prefix' => '{locale}', 'middleware' => 'setlocale'], function () {

    Route::get('/', [FrontendController::class, 'home'])->name('home');
    Route::get('/sculptures', [FrontendController::class, 'sculptures'])->name('sculptures.index');
    Route::get('/sculptures/{slug}', [FrontendController::class, 'sculptureShow'])->name('sculpture.show');
    Route::get('/sculptures/{slug}/360', [FrontendController::class, 'panorama'])->name('sculpture.panorama');
    Route::get('/about', [FrontendController::class, 'about'])->name('about');
    Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');

});

Route::get('/lang/{code}', [FrontendController::class, 'switchLang'])->name('lang.switch');

Route::get('/{locale}/{slug}', [App\Http\Controllers\FrontendController::class, 'page'])
    ->where('locale', '[a-z]{2}')
    ->where('slug', '[a-z0-9\-]+')
    ->name('page.show');
