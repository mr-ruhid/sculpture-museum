<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sculpture extends Model
{
    protected $fillable = [
        'slug',
        'year',
        'opening_date',
        'dimensions',
        'latitude',
        'longitude',
        'condition',
        'registration_info',
        'main_image',
        'panorama_embed',
        'is_published',
        'sort_order',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'opening_date' => 'date',
        'sort_order' => 'integer',
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
}
