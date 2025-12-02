<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_year_id',
        'name',
        'status',
    ];

    // RELATIONSHIPS

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function classSchedules(): HasMany
    {
        return $this->hasMany(ClassSchedule::class);
    }

    public function enrollmentHistory(): HasMany
    {
        return $this->hasMany(StudentEnrollmentHistory::class);
    }

    public function gwaCache(): HasMany
    {
        return $this->hasMany(StudentGwaCache::class);
    }

    // SCOPES

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'Closed');
    }

    // HELPERS

    public function isActive(): bool
    {
        return $this->status === 'Active';
    }

    public function close(): void
    {
        $this->update(['status' => 'Closed']);
    }
}
