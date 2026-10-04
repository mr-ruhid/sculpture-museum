<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    protected $fillable = [
        'ip',
        'attempts',
        'blocked_until',
        'last_attempt_at',
        'user_agent',
    ];

    protected $casts = [
        'attempts' => 'integer',
        'blocked_until' => 'datetime',
        'last_attempt_at' => 'datetime',
    ];

    public const MAX_ATTEMPTS = 5;
    public const BLOCK_HOURS = 1;

    public static function findForIp(string $ip): ?self
    {
        return static::where('ip', $ip)->first();
    }

    public function isBlocked(): bool
    {
        return $this->blocked_until && $this->blocked_until->isFuture();
    }

    public function remainingMinutes(): int
    {
        if (!$this->isBlocked()) {
            return 0;
        }

        return (int) ceil(now()->diffInMinutes($this->blocked_until));
    }

    public static function registerFailure(string $ip, ?string $userAgent = null): self
    {
        $attempt = static::firstOrCreate(['ip' => $ip]);

        $attempt->attempts += 1;
        $attempt->last_attempt_at = now();

        if ($userAgent) {
            $attempt->user_agent = substr($userAgent, 0, 500);
        }

        if ($attempt->attempts >= self::MAX_ATTEMPTS) {
            $attempt->blocked_until = now()->addHours(self::BLOCK_HOURS);
        }

        $attempt->save();

        return $attempt;
    }

    public static function clear(string $ip): void
    {
        static::where('ip', $ip)->delete();
    }
}
