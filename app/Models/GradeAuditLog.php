<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradeAuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'quarterly_grade_id',
        'user_id',
        'old_grade',
        'new_grade',
        'field_changed',
        'reason',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'old_grade' => 'decimal:2',
            'new_grade' => 'decimal:2',
        ];
    }

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function quarterlyGrade(): BelongsTo
    {
        return $this->belongsTo(QuarterlyGrade::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ==========================================
    // SCOPES
    // ==========================================

    public function scopeForGrade($query, $quarterlyGradeId)
    {
        return $query->where('quarterly_grade_id', $quarterlyGradeId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ==========================================
    // HELPERS
    // ==========================================

    public static function log(
        QuarterlyGrade $grade,
        User $user,
        ?float $oldGrade,
        float $newGrade,
        string $reason,
        string $fieldChanged = 'final_grade',
        ?string $ipAddress = null
    ): self {
        return static::create([
            'quarterly_grade_id' => $grade->id,
            'user_id' => $user->id,
            'old_grade' => $oldGrade,
            'new_grade' => $newGrade,
            'field_changed' => $fieldChanged,
            'reason' => $reason,
            'ip_address' => $ipAddress ?? request()->ip(),
        ]);
    }
}
