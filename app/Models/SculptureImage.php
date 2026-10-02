<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SculptureImage extends Model
{
    protected $fillable = [
        'sculpture_id',
        'path',
        'sort_order',
    ];

    public function sculpture()
    {
        return $this->belongsTo(Sculpture::class);
    }
}
