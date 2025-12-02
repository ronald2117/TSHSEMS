<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeTransmutation extends Model
{
    use HasFactory;

    protected $fillable = [
        'min_score',
        'max_score',
        'transmuted_grade',
    ];

    protected function casts(): array
    {
        return [
            'min_score' => 'decimal:2',
            'max_score' => 'decimal:2',
            'transmuted_grade' => 'integer',
        ];
    }

    // ==========================================
    // HELPERS
    // ==========================================

    /**
     * Transmute a raw percentage score to a final grade
     */
    public static function transmute(float $rawScore): int
    {
        $transmutation = static::where('min_score', '<=', $rawScore)
            ->where('max_score', '>=', $rawScore)
            ->first();

        return $transmutation?->transmuted_grade ?? 60; // Default to 60 if not found
    }

    /**
     * Get the remarks based on the transmuted grade
     */
    public static function getRemarks(int $transmutedGrade): string
    {
        return $transmutedGrade >= 75 ? 'Passed' : 'Failed';
    }

    /**
     * Get honors based on GWA
     */
    public static function getHonors(float $gwa): ?string
    {
        if ($gwa >= 98) {
            return 'With Highest Honors';
        } elseif ($gwa >= 95) {
            return 'With High Honors';
        } elseif ($gwa >= 90) {
            return 'With Honors';
        }
        return null;
    }
}
