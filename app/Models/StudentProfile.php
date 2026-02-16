<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lrn',
        'current_section_id',
        'strand_id',
        'gender',
        'guardian_name',
        'guardian_contact',
        'emergency_contacts',
        'birthdate',
        'address',
    ];

    protected function casts(): array
    {
        return [
            'birthdate' => 'date',
            'emergency_contacts' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function currentSection(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'current_section_id');
    }

    public function strand(): BelongsTo
    {
        return $this->belongsTo(Strand::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->user->full_name;
    }

    public function getGradeLevelAttribute(): ?int
    {
        return $this->currentSection?->grade_level;
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birthdate?->age;
    }

    // Delegated relationships via User
    public function enrollments()
    {
        return $this->user->enrollments();
    }

    public function quarterlyGrades()
    {
        return $this->user->quarterlyGrades();
    }

    public function attendances()
    {
        return $this->user->attendances();
    }

    public function scores()
    {
        return $this->user->scores();
    }
}
