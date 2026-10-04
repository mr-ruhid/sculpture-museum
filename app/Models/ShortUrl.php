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
                return url("/{$locale}/{$p->slug}");

            case 'sculptures_pair':
                $ids = $this->target_params['ids'] ?? [];
                if (count($ids) < 2) return null;
                $first = Sculpture::find($ids[0]);
                if (!$first) return null;
                $firstSlug = $first->slug;
                return url("/{$locale}/{$firstSlug}");
        }

        return null;
    }
}
