<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentScore extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'assessment_id',
        'student_id',
        'score',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
        ];
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function getPercentageAttribute(): float
    {
        if ($this->assessment->max_score == 0) {
            return 0;
        }
        return ($this->score / $this->assessment->max_score) * 100;
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeForAssessment($query, $assessmentId)
    {
        return $query->where('assessment_id', $assessmentId);
    }
}
