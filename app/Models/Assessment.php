<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assessment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'class_schedule_id',
        'title',
        'type',
        'max_score',
        'quarter',
        'is_published',
        'assessment_date',
    ];

    protected function casts(): array
    {
        return [
            'max_score' => 'integer',
            'quarter' => 'integer',
            'is_published' => 'boolean',
            'assessment_date' => 'date',
        ];
    }

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function classSchedule(): BelongsTo
    {
        return $this->belongsTo(ClassSchedule::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(StudentScore::class);
    }

    // ==========================================
    // ACCESSORS
    // ==========================================

    public function getTypeDisplayAttribute(): string
    {
        return match ($this->type) {
            'written_work' => 'Written Work',
            'performance_task' => 'Performance Task',
            'quarterly_assessment' => 'Quarterly Assessment',
            default => $this->type,
        };
    }

    // ==========================================
    // SCOPES
    // ==========================================

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeForQuarter($query, int $quarter)
    {
        return $query->where('quarter', $quarter);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeWrittenWorks($query)
    {
        return $query->where('type', 'written_work');
    }

    public function scopePerformanceTasks($query)
    {
        return $query->where('type', 'performance_task');
    }

    public function scopeQuarterlyAssessments($query)
    {
        return $query->where('type', 'quarterly_assessment');
    }

    // ==========================================
    // HELPERS
    // ==========================================

    public function publish(): void
    {
        $this->update(['is_published' => true]);
    }

    public function unpublish(): void
    {
        $this->update(['is_published' => false]);
    }

    public function getStudentScore($studentId): ?StudentScore
    {
        return $this->scores()->where('student_id', $studentId)->first();
    }

    public function getAverageScore(): float
    {
        return $this->scores()->avg('score') ?? 0;
    }

    public function getHighestScore(): float
    {
        return $this->scores()->max('score') ?? 0;
    }

    public function getLowestScore(): float
    {
        return $this->scores()->min('score') ?? 0;
    }
}
