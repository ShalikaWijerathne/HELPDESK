<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role_id'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function ticketsRaised(): HasMany
    {
        return $this->hasMany(Ticket::class, 'requester_id');
    }

    public function ticketsAssigned(): HasMany
    {
        return $this->hasMany(Ticket::class, 'assignee_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function isAdmin(): bool
    {
        return $this->role?->name === Role::ADMIN;
    }

    public function isStaff(): bool
    {
        return $this->role?->name === Role::STAFF;
    }

    public function isUser(): bool
    {
        return $this->role?->name === Role::USER;
    }

    public function isStaffOrAdmin(): bool
    {
        return $this->isStaff() || $this->isAdmin();
    }

    public function unreadNotificationCount(): int
    {
        return $this->notifications()->whereNull('read_at')->count();
    }
}
