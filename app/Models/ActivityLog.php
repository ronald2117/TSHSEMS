<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ==========================================
    // SCOPES
    // ==========================================

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeOfAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // ==========================================
    // LOGGING HELPERS
    // ==========================================

    /**
     * Log an activity
     */
    public static function log(
        string $action,
        string $description,
        ?User $user = null,
        ?string $ipAddress = null
    ): self {
        return static::create([
            'user_id' => $user?->id ?? auth()->id(),
            'action' => $action,
            'description' => $description,
            'ip_address' => $ipAddress ?? request()->ip(),
        ]);
    }

    /**
     * Log a login event
     */
    public static function logLogin(User $user): self
    {
        return static::log('LOGIN', "User {$user->full_name} logged in", $user);
    }

    /**
     * Log a logout event
     */
    public static function logLogout(User $user): self
    {
        return static::log('LOGOUT', "User {$user->full_name} logged out", $user);
    }

    /**
     * Log a grade update
     */
    public static function logGradeUpdate(
        User $user,
        QuarterlyGrade $grade,
        string $details
    ): self {
        return static::log(
            'UPDATE_GRADE',
            "Grade updated for student ID {$grade->student_id}: {$details}",
            $user
        );
    }

    /**
     * Log a grade submission
     */
    public static function logGradeSubmission(User $user, int $count): self
    {
        return static::log(
            'SUBMIT_GRADES',
            "Submitted {$count} grades for approval",
            $user
        );
    }

    /**
     * Log a grade approval
     */
    public static function logGradeApproval(User $user, int $count): self
    {
        return static::log(
            'APPROVE_GRADES',
            "Approved {$count} grades",
            $user
        );
    }

    // ==========================================
    // ACTION CONSTANTS
    // ==========================================

    public const ACTION_LOGIN = 'LOGIN';
    public const ACTION_LOGOUT = 'LOGOUT';
    public const ACTION_UPDATE_GRADE = 'UPDATE_GRADE';
    public const ACTION_SUBMIT_GRADES = 'SUBMIT_GRADES';
    public const ACTION_APPROVE_GRADES = 'APPROVE_GRADES';
    public const ACTION_RETURN_GRADES = 'RETURN_GRADES';
    public const ACTION_CREATE_USER = 'CREATE_USER';
    public const ACTION_UPDATE_USER = 'UPDATE_USER';
    public const ACTION_DELETE_USER = 'DELETE_USER';
    public const ACTION_ENROLL_STUDENT = 'ENROLL_STUDENT';
    public const ACTION_CREATE_SECTION = 'CREATE_SECTION';
    public const ACTION_CREATE_SCHEDULE = 'CREATE_SCHEDULE';
    public const ACTION_RECORD_ATTENDANCE = 'RECORD_ATTENDANCE';
    public const ACTION_PROCESS_DOCUMENT = 'PROCESS_DOCUMENT';
}
