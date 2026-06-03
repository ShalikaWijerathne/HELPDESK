<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SlaPolicy extends Model
{
    protected $fillable = ['priority_id', 'response_minutes', 'resolution_minutes'];

    public function priority(): BelongsTo
    {
        return $this->belongsTo(Priority::class);
    }
}
