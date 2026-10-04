<?php

namespace App\Http\Middleware;

use App\Models\LoginAttempt;
use Closure;
use Illuminate\Http\Request;

class CheckIpBlocked
{
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        $attempt = LoginAttempt::findForIp($ip);

        if ($attempt && $attempt->isBlocked()) {
            $minutes = $attempt->remainingMinutes();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Çox sayda uğursuz cəhd. Hesabınız müvəqqəti olaraq bloklandı.',
                    'blocked_until' => $attempt->blocked_until,
                    'remaining_minutes' => $minutes,
                ], 429);
            }

            return back()->withErrors([
                'name' => 'Çox sayda uğursuz cəhd. ' . $minutes . ' dəqiqə sonra yenidən cəhd edin.',
            ])->withInput($request->only('name'));
        }

        return $next($request);
    }
}
