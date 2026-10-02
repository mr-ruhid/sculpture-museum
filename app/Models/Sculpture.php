<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Sculpture extends Model
{
    protected $fillable = [
        'slug',
        'sculptor',
        'architect',
        'year',
        'opening_date',
        'material',
        'dimensions',
        'style',
        'city',
        'address',
        'latitude',
        'longitude',
        'condition',
        'registration_info',
        'main_image',
        'panorama_embed',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'opening_date' => 'date',
    ];

    public function translations()
    {
        return $this->hasMany(SculptureTranslation::class);
    }

    public function images()
    {
        return $this->hasMany(SculptureImage::class)->orderBy('sort_order');
    }

    public function translation($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'en')
            ?? $this->translations->first();
    }

    protected static function booted(): void
    {
        static::creating(function ($sculpture) {
            if (empty($sculpture->slug)) {
                $sculpture->slug = Str::slug($sculpture->translations->first()->title ?? 'sculpture-' . time());
            }
        });
    }
}
