<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department',
        'specialization',
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ==========================================
    // ACCESSORS
    // ==========================================

    public function getFullNameAttribute(): string
    {
        return $this->user->full_name;
    }

    // ==========================================
    // DELEGATED RELATIONSHIPS (via User)
    // ==========================================

    public function advisedSections()
    {
        return $this->user->advisedSections();
    }

    public function classSchedules()
    {
        return $this->user->classSchedules();
    }
}
