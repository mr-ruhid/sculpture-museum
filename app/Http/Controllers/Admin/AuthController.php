<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required'],
            'password' => ['required'],
        ]);

        $ip = $request->ip();
        $userAgent = $request->userAgent();

        if (!Auth::attempt($credentials)) {
            LoginAttempt::registerFailure($ip, $userAgent);

            $attempt = LoginAttempt::findForIp($ip);
            $remaining = $attempt && $attempt->isBlocked()
                ? ' Hesabınız ' . $attempt->remainingMinutes() . ' dəqiqə bloklandı.'
                : '';

            return back()->withErrors([
                'name' => 'İstifadəçi adı və ya şifrə yanlışdır.' . $remaining,
            ])->withInput($request->only('name'));
        }

        LoginAttempt::clear($ip);

        $user = Auth::user();

        if ($user->two_factor_enabled) {
            $user->generateTwoFactorCode();
            Mail::raw("Giriş kodunuz: {$user->two_factor_code}", function ($message) use ($user) {
                $message->to($user->email)->subject('Giriş təsdiqi');
            });

            return redirect()->route('admin.twofactor.show');
        }

        $request->session()->regenerate();
        return redirect()->route('admin.dashboard');
    }

    public function showTwoFactor()
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        return view('admin.auth.twofactor');
    }

    public function verifyTwoFactor(Request $request)
    {
        $request->validate(['code' => ['required', 'digits:6']]);

        $user = Auth::user();

        if (
            $user->two_factor_code !== $request->code ||
            !$user->two_factor_expires_at ||
            $user->two_factor_expires_at->isPast()
        ) {
            return back()->withErrors(['code' => 'Kod yanlışdır və ya vaxtı bitib.']);
        }

        $user->resetTwoFactorCode();
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
