<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class categories extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'is_published',
        'parent_id'
    ];

    protected $casts = [
        'is_published' => 'boolean'
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(posts::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(categories::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(categories::class, 'parent_id');
    }
}
