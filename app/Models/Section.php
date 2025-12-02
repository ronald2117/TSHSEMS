<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_year_id',
        'name',
        'grade_level',
        'strand_id',
        'adviser_id',
        'max_students',
    ];

    protected function casts(): array
    {
        return [
            'grade_level' => 'integer',
            'max_students' => 'integer',
        ];
    }

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function strand(): BelongsTo
    {
        return $this->belongsTo(Strand::class);
    }

    public function adviser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adviser_id');
    }

    public function studentProfiles(): HasMany
    {
        return $this->hasMany(StudentProfile::class, 'current_section_id');
    }

    public function classSchedules(): HasMany
    {
        return $this->hasMany(ClassSchedule::class);
    }

    public function enrollmentHistory(): HasMany
    {
        return $this->hasMany(StudentEnrollmentHistory::class);
    }

    // ==========================================
    // ACCESSORS
    // ==========================================

    public function getFullNameAttribute(): string
    {
        return "Grade {$this->grade_level} - {$this->name} ({$this->strand->code})";
    }

    public function getStudentCountAttribute(): int
    {
        return $this->studentProfiles()->count();
    }

    public function getAvailableSlotsAttribute(): int
    {
        return max(0, $this->max_students - $this->student_count);
    }

    // ==========================================
    // SCOPES
    // ==========================================

    public function scopeForSchoolYear($query, $schoolYearId)
    {
        return $query->where('school_year_id', $schoolYearId);
    }

    public function scopeForGradeLevel($query, int $gradeLevel)
    {
        return $query->where('grade_level', $gradeLevel);
    }

    public function scopeForStrand($query, $strandId)
    {
        return $query->where('strand_id', $strandId);
    }

    // ==========================================
    // HELPERS
    // ==========================================

    public function isFull(): bool
    {
        return $this->student_count >= $this->max_students;
    }

    public function hasAvailableSlots(): bool
    {
        return !$this->isFull();
    }

    public function getStudents()
    {
        return $this->studentProfiles()
            ->with('user')
            ->get()
            ->pluck('user');
    }
}
