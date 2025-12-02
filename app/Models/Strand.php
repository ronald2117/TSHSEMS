<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Strand extends Model
{
    use HasFactory;

    protected $fillable = [
        'track_id',
        'code',
        'name',
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function track(): BelongsTo
    {
        return $this->belongsTo(Track::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    public function studentProfiles(): HasMany
    {
        return $this->hasMany(StudentProfile::class);
    }

    public function strandSubjects(): HasMany
    {
        return $this->hasMany(StrandSubject::class);
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'strand_subjects')
            ->withPivot(['grade_level', 'semester', 'is_required'])
            ->withTimestamps();
    }

    // ==========================================
    // ACCESSORS
    // ==========================================

    public function getFullNameAttribute(): string
    {
        return "{$this->code} - {$this->name}";
    }

    public function getDisplayNameAttribute(): string
    {
        return "{$this->track->code} - {$this->code}";
    }

    // ==========================================
    // HELPERS
    // ==========================================

    public function getSubjectsForGradeLevel(int $gradeLevel, ?string $semester = null)
    {
        $query = $this->strandSubjects()->where('grade_level', $gradeLevel);
        
        if ($semester) {
            $query->where('semester', $semester);
        }
        
        return $query->with('subject')->get();
    }
}
