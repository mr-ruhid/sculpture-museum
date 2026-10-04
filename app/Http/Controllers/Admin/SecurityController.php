<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginAttempt;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    public function blockedIps()
    {
        $attempts = LoginAttempt::orderByDesc('blocked_until')
            ->orderByDesc('last_attempt_at')
            ->paginate(30);

        $stats = [
            'total' => LoginAttempt::count(),
            'blocked' => LoginAttempt::where('blocked_until', '>', now())->count(),
            'today' => LoginAttempt::whereDate('last_attempt_at', today())->count(),
        ];

        return view('admin.security.blocked-ips', compact('attempts', 'stats'));
    }

    public function unblock(LoginAttempt $attempt)
    {
        $attempt->delete();

        return back()->with('success', 'IP blokdan çıxarıldı.');
    }

    public function clearAll()
    {
        LoginAttempt::truncate();

        return back()->with('success', 'Bütün cəhd qeydləri silindi.');
    }
}
