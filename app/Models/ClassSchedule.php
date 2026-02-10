<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'section_id',
        'subject_id',
        'teacher_id',
        'academic_period_id',
        'schedule_time',
        'room',
        'schedule_details',
    ];

    protected function casts(): array
    {
        return [
            'schedule_details' => 'array',
        ];
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    // Accessor to get school year through academic period
    public function getSchoolYearAttribute()
    {
        return $this->academicPeriod?->schoolYear;
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(StudentSubjectEnrollment::class);
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }

    public function quarterlyGrades(): HasMany
    {
        return $this->hasMany(QuarterlyGrade::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return "{$this->subject->code} - {$this->section->full_name}";
    }

    public function getEnrolledCountAttribute(): int
    {
        return $this->enrollments()->where('status', 'enrolled')->count();
    }

    public function scopeForTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    public function scopeForSection($query, $sectionId)
    {
        return $query->where('section_id', $sectionId);
    }

    public function scopeForPeriod($query, $academicPeriodId)
    {
        return $query->where('academic_period_id', $academicPeriodId);
    }

    public function getEnrolledStudents()
    {
        return $this->enrollments()
            ->where('status', 'enrolled')
            ->with('student.studentProfile')
            ->get()
            ->pluck('student');
    }

    public function getAssessmentsForQuarter(int $quarter)
    {
        return $this->assessments()->where('quarter', $quarter)->get();
    }

    public function getGradingComponent(): ?GradingComponent
    {
        return $this->subject->getGradingComponent();
    }
}
