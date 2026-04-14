<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'department',
        'specialization',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->user->full_name;
    }

    // Delegated relationships via User
    public function advisedSections()
    {
        return $this->user->advisedSections();
    }

    public function classSchedules()
    {
        return $this->user->classSchedules();
    }
}
