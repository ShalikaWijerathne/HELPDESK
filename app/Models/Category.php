<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function kbArticles(): HasMany
    {
        return $this->hasMany(KbArticle::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
