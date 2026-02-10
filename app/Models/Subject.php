<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'type',
        'units',
        'is_active',
        'learning_competencies',
    ];

    protected function casts(): array
    {
        return [
            'units' => 'integer',
            'is_active' => 'boolean',
            'learning_competencies' => 'array',
        ];
    }

    public function strandSubjects(): HasMany
    {
        return $this->hasMany(StrandSubject::class);
    }

    public function strands(): BelongsToMany
    {
        return $this->belongsToMany(Strand::class, 'strand_subjects')
            ->withPivot(['grade_level', 'semester', 'is_required'])
            ->withTimestamps();
    }

    public function classSchedules(): HasMany
    {
        return $this->hasMany(ClassSchedule::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->code} - {$this->name}";
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeCore($query)
    {
        return $query->where('type', 'Core');
    }

    public function scopeApplied($query)
    {
        return $query->where('type', 'Applied');
    }

    public function scopeSpecialized($query)
    {
        return $query->where('type', 'Specialized');
    }

    public function getGradingComponent(): ?GradingComponent
    {
        return GradingComponent::where('subject_type', $this->type)->first();
    }
}
