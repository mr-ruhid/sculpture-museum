<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function wikis()
    {
        return view('admin.wikis.index');
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function about()
    {
        return view('admin.about');
    }
}
