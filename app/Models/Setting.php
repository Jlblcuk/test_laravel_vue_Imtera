<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'yandex_url',
        'rating',
        'reviews_count',
        'cached_reviews',
    ];

    protected $casts = [
        'cached_reviews' => 'array',
    ];
}
