<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentGwaCache extends Model
{
    use HasFactory;

    protected $table = 'student_gwa_cache';

    protected $fillable = [
        'student_id',
        'academic_period_id',
        'quarter',
        'gwa',
        'honors',
        'computed_at',
    ];

    protected function casts(): array
    {
        return [
            'quarter' => 'integer',
            'gwa' => 'decimal:2',
            'computed_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeForQuarter($query, int $quarter)
    {
        return $query->where('quarter', $quarter);
    }

    public function scopeWithHonors($query)
    {
        return $query->whereNotNull('honors');
    }

    public static function computeAndCache(
        User $student,
        AcademicPeriod $period,
        int $quarter
    ): self {
        // Get all approved quarterly grades for the student in this quarter
        $grades = QuarterlyGrade::where('student_id', $student->id)
            ->where('quarter', $quarter)
            ->where('status', 'Approved')
            ->whereHas('classSchedule', function ($query) use ($period) {
                $query->where('academic_period_id', $period->id);
            })
            ->get();

        if ($grades->isEmpty()) {
            $gwa = 0;
        } else {
            $gwa = $grades->avg('final_grade');
        }

        $honors = GradeTransmutation::getHonors($gwa);

        return static::updateOrCreate(
            [
                'student_id' => $student->id,
                'academic_period_id' => $period->id,
                'quarter' => $quarter,
            ],
            [
                'gwa' => $gwa,
                'honors' => $honors,
                'computed_at' => now(),
            ]
        );
    }
}
