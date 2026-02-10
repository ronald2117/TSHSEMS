<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentEnrollmentHistory extends Model
{
    use HasFactory;

    protected $table = 'student_enrollment_history';

    protected $fillable = [
        'student_id',
        'section_id',
        'academic_period_id',
        'grade_level',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'grade_level' => 'integer',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeForPeriod($query, $academicPeriodId)
    {
        return $query->where('academic_period_id', $academicPeriodId);
    }

    public function scopeEnrolled($query)
    {
        // Handle both capitalized and lowercase for legacy data compatibility
        return $query->whereIn('status', ['Enrolled', 'enrolled']);
    }
}
