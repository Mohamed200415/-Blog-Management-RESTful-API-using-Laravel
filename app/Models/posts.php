<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class posts extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'category_id',
        'image_thumbnail',
        'image_content',
        'is_published',
        'published_at',
        'author'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(categories::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(comments::class);
    }
}
