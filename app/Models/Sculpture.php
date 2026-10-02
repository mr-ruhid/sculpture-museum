<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Sculpture extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'sculptor',
        'year',
        'material',
        'location',
        'description',
        'image',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($sculpture) {
            if (empty($sculpture->slug)) {
                $sculpture->slug = Str::slug($sculpture->title);
            }
        });
    }
}
