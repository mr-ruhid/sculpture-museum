<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class CacheController extends Controller
{
    public function index()
    {
        return view('admin.cache');
    }

    public function clear()
    {
        Artisan::call('cache:clear');
        return back()->with('success', 'Tətbiq keşi təmizləndi.');
    }

    public function config()
    {
        Artisan::call('config:clear');
        Artisan::call('config:cache');
        return back()->with('success', 'Konfiqurasiya keşi yeniləndi.');
    }

    public function route()
    {
        Artisan::call('route:clear');
        Artisan::call('route:cache');
        return back()->with('success', 'Route keşi yeniləndi.');
    }

    public function view()
    {
        Artisan::call('view:clear');
        return back()->with('success', 'View keşi təmizləndi.');
    }
}
