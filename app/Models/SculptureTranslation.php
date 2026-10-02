<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SculptureTranslation extends Model
{
    protected $fillable = [
        'sculpture_id',
        'locale',
        'title',
        'short_description',
        'description',
        'history',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    public function sculpture()
    {
        return $this->belongsTo(Sculpture::class);
    }
}
