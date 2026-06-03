<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    const STATUS_OPEN        = 'open';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_ON_HOLD     = 'on_hold';
    const STATUS_RESOLVED    = 'resolved';
    const STATUS_CLOSED      = 'closed';

    const STATUSES = [
        self::STATUS_OPEN,
        self::STATUS_IN_PROGRESS,
        self::STATUS_ON_HOLD,
        self::STATUS_RESOLVED,
        self::STATUS_CLOSED,
    ];

    protected $fillable = [
        'reference', 'subject', 'description',
        'category_id', 'priority_id', 'status',
        'requester_id', 'logged_by_id', 'assignee_id',
        'sla_due_at', 'is_breached', 'kb_article_id',
    ];

    protected $casts = [
        'sla_due_at'  => 'datetime',
        'is_breached' => 'boolean',
    ];

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function loggedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'logged_by_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function priority(): BelongsTo
    {
        return $this->belongsTo(Priority::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TicketComment::class)->orderBy('created_at');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    public function kbArticle(): BelongsTo
    {
        return $this->belongsTo(KbArticle::class);
    }

    public function isResolved(): bool
    {
        return in_array($this->status, [self::STATUS_RESOLVED, self::STATUS_CLOSED]);
    }

    public function statusLabel(): string
    {
        return ucwords(str_replace('_', ' ', $this->status));
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_OPEN        => 'primary',
            self::STATUS_IN_PROGRESS => 'warning',
            self::STATUS_ON_HOLD     => 'secondary',
            self::STATUS_RESOLVED    => 'success',
            self::STATUS_CLOSED      => 'dark',
            default                  => 'light',
        };
    }

    public function slaMinutesRemaining(): ?int
    {
        if (!$this->sla_due_at) {
            return null;
        }
        return (int) now()->diffInMinutes($this->sla_due_at, false);
    }

    public static function generateReference(): string
    {
        $last = self::max('id') ?? 0;
        return 'TKT-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }
}
