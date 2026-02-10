<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'type',
        'status',
        'copies',
        'purpose',
        'fee',
        'processed_by',
        'claimed_at',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'copies' => 'integer',
            'fee' => 'decimal:2',
            'claimed_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'Processing');
    }

    public function scopeReady($query)
    {
        return $query->where('status', 'Ready');
    }

    public function scopeClaimed($query)
    {
        return $query->where('status', 'Claimed');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'Rejected');
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function startProcessing(User $processor): void
    {
        $this->update([
            'status' => 'Processing',
            'processed_by' => $processor->id,
        ]);
    }

    public function markReady(): void
    {
        $this->update(['status' => 'Ready']);
    }

    public function markClaimed(): void
    {
        $this->update([
            'status' => 'Claimed',
            'claimed_at' => now(),
        ]);
    }

    public function reject(string $reason): void
    {
        $this->update([
            'status' => 'Rejected',
            'rejection_reason' => $reason,
        ]);
    }

    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'Processing';
    }

    public function isReady(): bool
    {
        return $this->status === 'Ready';
    }

    public function isClaimed(): bool
    {
        return $this->status === 'Claimed';
    }

    public function isRejected(): bool
    {
        return $this->status === 'Rejected';
    }

    public static function documentTypes(): array
    {
        return [
            'Form 137' => 'Form 137 (Permanent Record)',
            'Form 138' => 'Form 138 (Report Card)',
            'Certificate of Enrollment' => 'Certificate of Enrollment',
            'Certificate of Good Moral' => 'Certificate of Good Moral Character',
            'Diploma' => 'Diploma',
            'Transcript of Records' => 'Transcript of Records',
            'Other' => 'Other',
        ];
    }
}
