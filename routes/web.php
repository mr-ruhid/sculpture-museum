<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CacheController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\MaintenanceController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SculptureController;
use App\Http\Controllers\Admin\SecurityController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ShortUrlController;
use App\Http\Controllers\Admin\TranslationController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('ip.blocked')
        ->name('login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/twofactor', [AuthController::class, 'showTwoFactor'])->name('twofactor.show');
    Route::post('/twofactor', [AuthController::class, 'verifyTwoFactor'])->name('twofactor.verify');

    Route::middleware('auth')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/wikis', [SculptureController::class, 'index'])->name('wikis.index');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');

        Route::get('/about', [SettingsController::class, 'about'])->name('about');
        Route::post('/about', [SettingsController::class, 'aboutUpdate'])->name('about.update');

        Route::get('/settings/general', [SettingsController::class, 'general'])->name('settings.general');
        Route::post('/settings/general', [SettingsController::class, 'generalUpdate'])->name('settings.general.update');

        Route::get('/settings/contact', [SettingsController::class, 'contact'])->name('settings.contact');
        Route::post('/settings/contact', [SettingsController::class, 'contactUpdate'])->name('settings.contact.update');

        Route::get('/settings/social', [SettingsController::class, 'social'])->name('settings.social');
        Route::post('/settings/social', [SettingsController::class, 'socialUpdate'])->name('settings.social.update');

        Route::get('/settings/seo', [SettingsController::class, 'seo'])->name('settings.seo');
        Route::post('/settings/seo', [SettingsController::class, 'seoUpdate'])->name('settings.seo.update');

        Route::get('/settings/homepage', [SettingsController::class, 'homepage'])->name('settings.homepage');
        Route::post('/settings/homepage', [SettingsController::class, 'homepageUpdate'])->name('settings.homepage.update');

        Route::get('/settings/smtp', [SettingsController::class, 'smtp'])->name('settings.smtp');
        Route::post('/settings/smtp', [SettingsController::class, 'smtpUpdate'])->name('settings.smtp.update');

        Route::get('/settings/maintenance', [MaintenanceController::class, 'index'])->name('settings.maintenance');
        Route::post('/settings/maintenance/enable', [MaintenanceController::class, 'enable'])->name('settings.maintenance.enable');
        Route::post('/settings/maintenance/disable', [MaintenanceController::class, 'disable'])->name('settings.maintenance.disable');

        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::post('/profile/twofactor', [ProfileController::class, 'updateTwoFactor'])->name('profile.twofactor');

        Route::get('/languages', [LanguageController::class, 'index'])->name('languages.index');
        Route::put('/languages/{language}', [LanguageController::class, 'update'])->name('languages.update');
        Route::post('/languages/{language}/default', [LanguageController::class, 'setDefault'])->name('languages.default');
        Route::get('/languages/{language}/translations', [TranslationController::class, 'edit'])->name('translations.edit');
        Route::put('/languages/{language}/translations', [TranslationController::class, 'update'])->name('translations.update');

        Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
        Route::get('/pages/create', [PageController::class, 'create'])->name('pages.create');
        Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
        Route::get('/pages/{slug}/edit', [PageController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{slug}', [PageController::class, 'update'])->name('pages.update');
        Route::delete('/pages/{slug}', [PageController::class, 'destroy'])->name('pages.destroy');

        Route::get('/short-urls', [ShortUrlController::class, 'index'])->name('short-urls.index');
        Route::post('/short-urls', [ShortUrlController::class, 'store'])->name('short-urls.store');
        Route::put('/short-urls/{short_url}', [ShortUrlController::class, 'update'])->name('short-urls.update');
        Route::delete('/short-urls/{short_url}', [ShortUrlController::class, 'destroy'])->name('short-urls.destroy');

        Route::get('/security/blocked-ips', [SecurityController::class, 'blockedIps'])->name('security.blockedIps');
        Route::delete('/security/blocked-ips/{attempt}', [SecurityController::class, 'unblock'])->name('security.unblock');
        Route::delete('/security/clear-all', [SecurityController::class, 'clearAll'])->name('security.clearAll');

        Route::get('/cache', [CacheController::class, 'index'])->name('cache.index');
        Route::post('/cache/clear', [CacheController::class, 'clear'])->name('cache.clear');
        Route::post('/cache/config', [CacheController::class, 'config'])->name('cache.config');
        Route::post('/cache/route', [CacheController::class, 'route'])->name('cache.route');
        Route::post('/cache/view', [CacheController::class, 'view'])->name('cache.view');

        Route::post('/sculptures/update-sort', [SculptureController::class, 'updateSort'])->name('sculptures.updateSort');
        Route::resource('sculptures', SculptureController::class);
        Route::delete('/sculptures/images/{image}', [SculptureController::class, 'destroyImage'])->name('sculptures.images.destroy');
    });
});
