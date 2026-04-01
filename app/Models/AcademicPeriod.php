<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class AcademicPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_year_id',
        'name',
        'status',
    ];

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class, 'school_year_id', 'school_year_id');
    }

    public function classSchedules(): HasMany
    {
        return $this->hasMany(ClassSchedule::class);
    }

    /**
     * Get sections that have class schedules in this academic period
     */
    public function scheduledSections()
    {
        return $this->hasManyThrough(
            Section::class,
            ClassSchedule::class,
            'academic_period_id', // Foreign key on class_schedules table
            'id', // Foreign key on sections table
            'id', // Local key on academic_periods table
            'section_id' // Local key on class_schedules table
        )->distinct();
    }

    public function enrollmentHistory(): HasMany
    {
        return $this->hasMany(StudentEnrollmentHistory::class);
    }

    public function gwaCache(): HasMany
    {
        return $this->hasMany(StudentGwaCache::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'Closed');
    }

    public function isActive(): bool
    {
        return $this->status === 'Active';
    }

    public function close(): void
    {
        $this->update(['status' => 'Closed']);
    }
}
