<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StrandSubject extends Model
{
    use HasFactory;

    protected $fillable = [
        'strand_id',
        'subject_id',
        'grade_level',
        'semester',
        'is_required',
    ];

    protected function casts(): array
    {
        return [
            'grade_level' => 'integer',
            'is_required' => 'boolean',
        ];
    }

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function strand(): BelongsTo
    {
        return $this->belongsTo(Strand::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    // ==========================================
    // SCOPES
    // ==========================================

    public function scopeForGradeLevel($query, int $gradeLevel)
    {
        return $query->where('grade_level', $gradeLevel);
    }

    public function scopeForSemester($query, string $semester)
    {
        return $query->where('semester', $semester);
    }

    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    public function scopeElective($query)
    {
        return $query->where('is_required', false);
    }
}
