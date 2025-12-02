<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'is_public',
        'author_id',
        'target_role',
        'published_at',
        'expires_at',
        'is_pinned',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'is_pinned' => 'boolean',
            'published_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // ==========================================
    // SCOPES
    // ==========================================

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeForRole($query, ?string $role)
    {
        return $query->where(function ($q) use ($role) {
            $q->whereNull('target_role')
              ->orWhere('target_role', $role);
        });
    }

    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('published_at')
              ->orWhere('published_at', '<=', now());
        })->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    // ==========================================
    // HELPERS
    // ==========================================

    public function isActive(): bool
    {
        $published = is_null($this->published_at) || $this->published_at <= now();
        $notExpired = is_null($this->expires_at) || $this->expires_at > now();
        
        return $published && $notExpired;
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at <= now();
    }

    public function publish(): void
    {
        $this->update(['published_at' => now()]);
    }

    public function pin(): void
    {
        $this->update(['is_pinned' => true]);
    }

    public function unpin(): void
    {
        $this->update(['is_pinned' => false]);
    }

    /**
     * Get announcements visible to a user
     */
    public static function forUser(User $user)
    {
        return static::active()
            ->forRole($user->role)
            ->orderByDesc('is_pinned')
            ->orderByDesc('published_at')
            ->get();
    }

    /**
     * Get public announcements for landing page
     */
    public static function getPublic()
    {
        return static::public()
            ->active()
            ->orderByDesc('is_pinned')
            ->orderByDesc('published_at')
            ->get();
    }
}
