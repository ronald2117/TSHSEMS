<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradingComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_type',
        'written_weight',
        'performance_weight',
        'exam_weight',
    ];

    protected function casts(): array
    {
        return [
            'written_weight' => 'decimal:2',
            'performance_weight' => 'decimal:2',
            'exam_weight' => 'decimal:2',
        ];
    }

    public function scopeForType($query, string $subjectType)
    {
        return $query->where('subject_type', $subjectType);
    }

    public static function forSubjectType(string $type): ?self
    {
        return static::where('subject_type', $type)->first();
    }

    public function getTotalWeight(): float
    {
        return $this->written_weight + $this->performance_weight + $this->exam_weight;
    }

    public function isValid(): bool
    {
        return abs($this->getTotalWeight() - 1.00) < 0.001;
    }
}
