<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Priority extends Model
{
    protected $fillable = ['name', 'rank', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function slaPolicy(): HasOne
    {
        return $this->hasOne(SlaPolicy::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('rank');
    }

    public function badgeClass(): string
    {
        return match ($this->name) {
            'Urgent' => 'danger',
            'High'   => 'warning',
            'Medium' => 'info',
            default  => 'secondary',
        };
    }
}
