<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Language extends Model
{
    protected $fillable = ['code', 'name', 'flag', 'is_active', 'is_default'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    public static function syncFromFiles(): void
    {
        $path = lang_path();
        if (!File::isDirectory($path)) {
            return;
        }

        $codes = collect(File::files($path))
            ->filter(fn ($f) => $f->getExtension() === 'json')
            ->map(fn ($f) => $f->getFilenameWithoutExtension())
            ->values();

        foreach ($codes as $code) {
            static::firstOrCreate(
                ['code' => $code],
                ['name' => strtoupper($code), 'flag' => '', 'is_active' => true]
            );
        }
    }

    public static function active()
    {
        return static::where('is_active', true)->get();
    }

    public static function getDefault(): ?string
    {
        $default = static::where('is_default', true)->first();
        return $default?->code ?? config('app.locale');
    }

    public function setAsDefault(): void
    {
        static::query()->update(['is_default' => false]);
        $this->is_default = true;
        $this->is_active = true;
        $this->save();
    }
}
