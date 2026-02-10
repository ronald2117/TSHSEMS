<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'login_id',
        'email',
        'password',
        'role',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'avatar_path',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function getFullNameAttribute(): string
    {
        $name = "{$this->first_name} ";
        if ($this->middle_name) {
            $name .= strtoupper(substr($this->middle_name, 0, 1)) . ". ";
        }
        $name .= $this->last_name;
        if ($this->suffix) {
            $name .= " {$this->suffix}";
        }
        return trim($name);
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'academic_admin', 'registrar_admin', 'technical_admin']);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function studentProfile(): HasOne
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function teacherProfile(): HasOne
    {
        return $this->hasOne(TeacherProfile::class);
    }

    public function advisedSections(): HasMany
    {
        return $this->hasMany(Section::class, 'adviser_id');
    }

    public function classSchedules(): HasMany
    {
        return $this->hasMany(ClassSchedule::class, 'teacher_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(StudentSubjectEnrollment::class, 'student_id');
    }

    public function enrollmentHistory(): HasMany
    {
        return $this->hasMany(StudentEnrollmentHistory::class, 'student_id');
    }

    public function scores(): HasMany
    {
        return $this->hasMany(StudentScore::class, 'student_id');
    }

    public function quarterlyGrades(): HasMany
    {
        return $this->hasMany(QuarterlyGrade::class, 'student_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    public function documentRequests(): HasMany
    {
        return $this->hasMany(DocumentRequest::class, 'student_id');
    }

    public function gwaCache(): HasMany
    {
        return $this->hasMany(StudentGwaCache::class, 'student_id');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function gradeAuditLogs(): HasMany
    {
        return $this->hasMany(GradeAuditLog::class);
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class, 'author_id');
    }

    public function submittedGrades(): HasMany
    {
        return $this->hasMany(QuarterlyGrade::class, 'submitted_by');
    }

    public function approvedGrades(): HasMany
    {
        return $this->hasMany(QuarterlyGrade::class, 'approved_by');
    }

    public function processedDocuments(): HasMany
    {
        return $this->hasMany(DocumentRequest::class, 'processed_by');
    }

    public function recordedAttendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'recorded_by');
    }
}
