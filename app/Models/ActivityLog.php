<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

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

    public static function logLogin(User $user): self
    {
        return static::log('LOGIN', "User {$user->full_name} logged in", $user);
    }

    public static function logLogout(User $user): self
    {
        return static::log('LOGOUT', "User {$user->full_name} logged out", $user);
    }

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

    public static function logGradeSubmission(User $user, int $count): self
    {
        return static::log(
            'SUBMIT_GRADES',
            "Submitted {$count} grades for approval",
            $user
        );
    }

    public static function logGradeApproval(User $user, int $count): self
    {
        return static::log(
            'APPROVE_GRADES',
            "Approved {$count} grades",
            $user
        );
    }

    public static function logGradeReturn(User $user, int $count, string $reason): self
    {
        return static::log(
            'RETURN_GRADES',
            "Returned {$count} grades. Reason: {$reason}",
            $user
        );
    }

    public static function logCreateUser(User $admin, User $newUser): self
    {
        return static::log(
            'CREATE_USER',
            "Created user: {$newUser->full_name} ({$newUser->role})",
            $admin
        );
    }

    public static function logUpdateUser(User $admin, User $updatedUser, string $changes): self
    {
        return static::log(
            'UPDATE_USER',
            "Updated user {$updatedUser->full_name}: {$changes}",
            $admin
        );
    }

    public static function logDeleteUser(User $admin, string $deletedUserName): self
    {
        return static::log(
            'DELETE_USER',
            "Deleted user: {$deletedUserName}",
            $admin
        );
    }

    public static function logEnrollStudent(User $admin, User $student, Section $section): self
    {
        return static::log(
            'ENROLL_STUDENT',
            "Enrolled student {$student->full_name} to section {$section->full_name}",
            $admin
        );
    }

    public static function logCreateSection(User $admin, Section $section): self
    {
        return static::log(
            'CREATE_SECTION',
            "Created section: {$section->full_name}",
            $admin
        );
    }

    public static function logCreateSchedule(User $admin, ClassSchedule $schedule): self
    {
        return static::log(
            'CREATE_SCHEDULE',
            "Created class schedule: {$schedule->display_name}",
            $admin
        );
    }

    public static function logRecordAttendance(User $teacher, int $studentCount, string $date): self
    {
        return static::log(
            'RECORD_ATTENDANCE',
            "Recorded attendance for {$studentCount} students on {$date}",
            $teacher
        );
    }

    public static function logProcessDocument(User $admin, DocumentRequest $request): self
    {
        return static::log(
            'PROCESS_DOCUMENT',
            "Processed document request ({$request->type}) for student ID {$request->student_id}",
            $admin
        );
    }

    public static function logCreateAnnouncement(User $admin, Announcement $announcement): self
    {
        return static::log(
            'CREATE_ANNOUNCEMENT',
            "Created announcement: {$announcement->title}",
            $admin
        );
    }

    public static function logUpdateAnnouncement(User $admin, Announcement $announcement): self
    {
        return static::log(
            'UPDATE_ANNOUNCEMENT',
            "Updated announcement: {$announcement->title}",
            $admin
        );
    }

    public static function logDeleteAnnouncement(User $admin, string $announcementTitle): self
    {
        return static::log(
            'DELETE_ANNOUNCEMENT',
            "Deleted announcement: {$announcementTitle}",
            $admin
        );
    }

    public static function logPublishAnnouncement(User $admin, Announcement $announcement): self
    {
        return static::log(
            'PUBLISH_ANNOUNCEMENT',
            "Published announcement: {$announcement->title}",
            $admin
        );
    }

    public static function logCreateAssessment(User $teacher, Assessment $assessment): self
    {
        return static::log(
            'CREATE_ASSESSMENT',
            "Created assessment: {$assessment->title} (Max Score: {$assessment->max_score})",
            $teacher
        );
    }

    public static function logUpdateAssessment(User $teacher, Assessment $assessment): self
    {
        return static::log(
            'UPDATE_ASSESSMENT',
            "Updated assessment: {$assessment->title}",
            $teacher
        );
    }

    public static function logDeleteAssessment(User $teacher, string $assessmentTitle): self
    {
        return static::log(
            'DELETE_ASSESSMENT',
            "Deleted assessment: {$assessmentTitle}",
            $teacher
        );
    }

    public static function logPublishAssessment(User $teacher, Assessment $assessment): self
    {
        return static::log(
            'PUBLISH_ASSESSMENT',
            "Published assessment: {$assessment->title}",
            $teacher
        );
    }

    public static function logCreateSubject(User $admin, Subject $subject): self
    {
        return static::log(
            'CREATE_SUBJECT',
            "Created subject: {$subject->full_name}",
            $admin
        );
    }

    public static function logUpdateSubject(User $admin, Subject $subject): self
    {
        return static::log(
            'UPDATE_SUBJECT',
            "Updated subject: {$subject->full_name}",
            $admin
        );
    }

    public static function logDeleteSubject(User $admin, string $subjectName): self
    {
        return static::log(
            'DELETE_SUBJECT',
            "Deleted subject: {$subjectName}",
            $admin
        );
    }

    public static function logUpdateSection(User $admin, Section $section): self
    {
        return static::log(
            'UPDATE_SECTION',
            "Updated section: {$section->full_name}",
            $admin
        );
    }

    public static function logDeleteSection(User $admin, string $sectionName): self
    {
        return static::log(
            'DELETE_SECTION',
            "Deleted section: {$sectionName}",
            $admin
        );
    }

    public static function logUpdateSchedule(User $admin, ClassSchedule $schedule): self
    {
        return static::log(
            'UPDATE_SCHEDULE',
            "Updated class schedule: {$schedule->display_name}",
            $admin
        );
    }

    public static function logDeleteSchedule(User $admin, string $scheduleName): self
    {
        return static::log(
            'DELETE_SCHEDULE',
            "Deleted class schedule: {$scheduleName}",
            $admin
        );
    }

    public static function logCreateTrack(User $admin, Track $track): self
    {
        return static::log(
            'CREATE_TRACK',
            "Created track: {$track->display_name}",
            $admin
        );
    }

    public static function logUpdateTrack(User $admin, Track $track): self
    {
        return static::log(
            'UPDATE_TRACK',
            "Updated track: {$track->display_name}",
            $admin
        );
    }

    public static function logDeleteTrack(User $admin, string $trackName): self
    {
        return static::log(
            'DELETE_TRACK',
            "Deleted track: {$trackName}",
            $admin
        );
    }

    public static function logCreateStrand(User $admin, Strand $strand): self
    {
        return static::log(
            'CREATE_STRAND',
            "Created strand: {$strand->full_name}",
            $admin
        );
    }

    public static function logUpdateStrand(User $admin, Strand $strand): self
    {
        return static::log(
            'UPDATE_STRAND',
            "Updated strand: {$strand->full_name}",
            $admin
        );
    }

    public static function logDeleteStrand(User $admin, string $strandName): self
    {
        return static::log(
            'DELETE_STRAND',
            "Deleted strand: {$strandName}",
            $admin
        );
    }

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
    public const ACTION_UPDATE_SECTION = 'UPDATE_SECTION';
    public const ACTION_DELETE_SECTION = 'DELETE_SECTION';
    public const ACTION_CREATE_SCHEDULE = 'CREATE_SCHEDULE';
    public const ACTION_UPDATE_SCHEDULE = 'UPDATE_SCHEDULE';
    public const ACTION_DELETE_SCHEDULE = 'DELETE_SCHEDULE';
    public const ACTION_RECORD_ATTENDANCE = 'RECORD_ATTENDANCE';
    public const ACTION_PROCESS_DOCUMENT = 'PROCESS_DOCUMENT';
    public const ACTION_CREATE_ANNOUNCEMENT = 'CREATE_ANNOUNCEMENT';
    public const ACTION_UPDATE_ANNOUNCEMENT = 'UPDATE_ANNOUNCEMENT';
    public const ACTION_DELETE_ANNOUNCEMENT = 'DELETE_ANNOUNCEMENT';
    public const ACTION_PUBLISH_ANNOUNCEMENT = 'PUBLISH_ANNOUNCEMENT';
    public const ACTION_CREATE_ASSESSMENT = 'CREATE_ASSESSMENT';
    public const ACTION_UPDATE_ASSESSMENT = 'UPDATE_ASSESSMENT';
    public const ACTION_DELETE_ASSESSMENT = 'DELETE_ASSESSMENT';
    public const ACTION_PUBLISH_ASSESSMENT = 'PUBLISH_ASSESSMENT';
    public const ACTION_CREATE_SUBJECT = 'CREATE_SUBJECT';
    public const ACTION_UPDATE_SUBJECT = 'UPDATE_SUBJECT';
    public const ACTION_DELETE_SUBJECT = 'DELETE_SUBJECT';
    public const ACTION_CREATE_TRACK = 'CREATE_TRACK';
    public const ACTION_UPDATE_TRACK = 'UPDATE_TRACK';
    public const ACTION_DELETE_TRACK = 'DELETE_TRACK';
    public const ACTION_CREATE_STRAND = 'CREATE_STRAND';
    public const ACTION_UPDATE_STRAND = 'UPDATE_STRAND';
    public const ACTION_DELETE_STRAND = 'DELETE_STRAND';
}
