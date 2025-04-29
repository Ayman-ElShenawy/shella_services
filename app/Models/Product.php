<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'user_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images():HasMany
    {    
        return $this->hasMany(Image::class);
    }
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function orders():HasMany
    {
        return $this->hasMany(Order::class);
    }
    public function rating():HasMany
    {
        return $this->hasMany(Rating::class);
    }

}
