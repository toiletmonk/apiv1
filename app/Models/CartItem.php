<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'user_id',
        'post_id',
        'quantity',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
