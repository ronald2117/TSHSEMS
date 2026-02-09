<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function academicPeriods(): HasMany
    {
        return $this->hasMany(AcademicPeriod::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrent($query)
    {
        return $query->where('is_active', true)->first();
    }

    public static function current(): ?self
    {
        return static::where('is_active', true)->first();
    }

    public function activate(): void
    {
        // Deactivate all other school years
        static::where('id', '!=', $this->id)->update(['is_active' => false]);
        $this->update(['is_active' => true]);
    }

    /**
     * Check if this school year is locked
     */
    public function getIsLockedAttribute(): bool
    {
        return SystemSetting::get("school_year_{$this->id}_locked", false);
    }

    /**
     * Lock this school year
     */
    public function lock(): void
    {
        SystemSetting::set("school_year_{$this->id}_locked", true);
    }

    /**
     * Unlock this school year
     */
    public function unlock(): void
    {
        SystemSetting::set("school_year_{$this->id}_locked", false);
    }
}
