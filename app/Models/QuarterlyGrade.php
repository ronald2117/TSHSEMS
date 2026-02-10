<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuarterlyGrade extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'class_schedule_id',
        'quarter',
        'initial_grade',
        'final_grade',
        'remarks',
        'status',
        'submitted_at',
        'submitted_by',
        'approved_at',
        'approved_by',
        'return_reason',
    ];

    protected function casts(): array
    {
        return [
            'quarter' => 'integer',
            'initial_grade' => 'decimal:2',
            'final_grade' => 'integer',
            'submitted_at' => 'datetime',
            'approved_at' => 'datetime',
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

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(GradeAuditLog::class);
    }

    public function getIsPassed(): bool
    {
        return $this->final_grade >= 75;
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeForQuarter($query, int $quarter)
    {
        return $query->where('quarter', $quarter);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'Draft');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'Submitted');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'Approved');
    }

    public function scopeReturned($query)
    {
        return $query->where('status', 'Returned');
    }

    public function scopePassed($query)
    {
        return $query->where('final_grade', '>=', 75);
    }

    public function scopeFailed($query)
    {
        return $query->where('final_grade', '<', 75);
    }

    public function submit(User $user): void
    {
        $this->update([
            'status' => 'Submitted',
            'submitted_at' => now(),
            'submitted_by' => $user->id,
        ]);
    }

    public function approve(User $user): void
    {
        $this->update([
            'status' => 'Approved',
            'approved_at' => now(),
            'approved_by' => $user->id,
            'return_reason' => null,
        ]);
    }

    public function return(User $user, string $reason): void
    {
        $this->update([
            'status' => 'Returned',
            'return_reason' => $reason,
        ]);
    }

    public function revertToDraft(): void
    {
        $this->update([
            'status' => 'Draft',
            'submitted_at' => null,
            'submitted_by' => null,
        ]);
    }

    public function isDraft(): bool
    {
        return $this->status === 'Draft';
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'Submitted';
    }

    public function isApproved(): bool
    {
        return $this->status === 'Approved';
    }

    public function isReturned(): bool
    {
        return $this->status === 'Returned';
    }

    public function canBeEdited(): bool
    {
        return in_array($this->status, ['Draft', 'Returned']);
    }

    public function canBeSubmitted(): bool
    {
        return in_array($this->status, ['Draft', 'Returned']);
    }
}
