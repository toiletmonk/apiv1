<?php

namespace App\Models;

use App\Policies\PostPolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    protected $fillable = [
        'title',
        'content',
        'price',
        'stripe_price_id',
        'user_id'
    ];

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    public function likes(): HasMany
    {
        return $this->reactions()->where('type', 'like');
    }

    public function dislikes(): HasMany
    {
        return $this->reactions()->where('type', 'dislike');
    }

    public function scopeFilter($query, array $filter)
    {
        return $query
            ->when($filter['category_id'] ?? null, fn ($q, $v) => $q->where('category_id', $v))
            ->when($filter['min_price'] ?? null, fn ($q, $v) => $q->where('price', '>=', $v))
            ->when($filter['max_price'] ?? null, fn ($q, $v) => $q->where('price', '<=', $v));
    }
}
