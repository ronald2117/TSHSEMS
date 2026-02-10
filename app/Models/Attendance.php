<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'class_schedule_id',
        'date',
        'status',
        'remarks',
        'recorded_by',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function classSchedule(): BelongsTo
    {
        return $this->belongsTo(ClassSchedule::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeForClass($query, $classScheduleId)
    {
        return $query->where('class_schedule_id', $classScheduleId);
    }

    public function scopeForDate($query, $date)
    {
        return $query->where('date', $date);
    }

    public function scopePresent($query)
    {
        return $query->where('status', 'Present');
    }

    public function scopeAbsent($query)
    {
        return $query->where('status', 'Absent');
    }

    public function scopeLate($query)
    {
        return $query->where('status', 'Late');
    }

    public function scopeExcused($query)
    {
        return $query->where('status', 'Excused');
    }

    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function isPresent(): bool
    {
        return $this->status === 'Present';
    }

    public function isAbsent(): bool
    {
        return $this->status === 'Absent';
    }

    public function isLate(): bool
    {
        return $this->status === 'Late';
    }

    public function isExcused(): bool
    {
        return $this->status === 'Excused';
    }

    // Get attendance summary for a student in a class
    public static function getSummary($studentId, $classScheduleId): array
    {
        $attendances = static::where('student_id', $studentId)
            ->where('class_schedule_id', $classScheduleId)
            ->get();

        return [
            'total' => $attendances->count(),
            'present' => $attendances->where('status', 'Present')->count(),
            'absent' => $attendances->where('status', 'Absent')->count(),
            'late' => $attendances->where('status', 'Late')->count(),
            'excused' => $attendances->where('status', 'Excused')->count(),
        ];
    }
}
