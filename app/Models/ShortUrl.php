<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShortUrl extends Model
{
    protected $fillable = [
        'code',
        'target_type',
        'target_id',
        'target_params',
        'note',
        'is_active',
        'hits',
    ];

    protected $casts = [
        'target_params' => 'array',
        'is_active' => 'boolean',
        'hits' => 'integer',
    ];

    public function incrementHits(): void
    {
        $this->increment('hits');
    }

    public function getFullUrlAttribute(): string
    {
        return url('/q/' . $this->code);
    }

    public function resolveUrl(string $locale): ?string
    {
        switch ($this->target_type) {
            case 'sculpture':
                $s = Sculpture::find($this->target_id);
                return $s ? url("/{$locale}/sculptures/{$s->slug}") : null;

            case 'page':
                $p = Page::find($this->target_id);
                if (!$p) return null;
                if ($p->slug === 'home') {
                    return url("/{$locale}");
                }
                $customView = 'theme.rjmuseum.pages.templates.' . $p->slug;
                if (view()->exists($customView)) {
                    return url("/{$locale}/{$p->slug}");
                }
                return url("/{$locale}/{$p->slug}");

            case 'sculptures_pair':
                $slugs = $this->target_params['slugs'] ?? [];
                if (count($slugs) < 1) return null;
                $first = Sculpture::where('slug', $slugs[0])->first();
                return $first ? url("/{$locale}/sculptures/{$first->slug}") : null;

            case 'custom':
                $url = $this->target_params['url'] ?? null;
                if (!$url) return null;
                if (str_starts_with($url, 'http')) return $url;
                return url("/{$locale}/" . ltrim($url, '/'));

            default:
                return null;
        }
    }
}
