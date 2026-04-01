<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\StudentProfile;
use App\Models\QuarterlyGrade;
use App\Models\Attendance;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        if ($user->isStudent()) {
            return $this->studentDashboard($user);
        } elseif ($user->isTeacher()) {
            return $this->teacherDashboard($user);
        } elseif ($user->isAdmin()) {
            return $this->adminDashboard($user);
        }

        return view('dashboard.index');
    }

    private function studentDashboard($user): View
    {
        $profile = $user->studentProfile()->with(['currentSection', 'strand'])->first();
        $grades = QuarterlyGrade::where('student_id', $user->id)
            ->with('classSchedule.subject')
            ->where('status', 'approved')
            ->get();
        
        $recentGrades = $grades->sortByDesc('created_at')->take(6);

        // Get announcements for students
        $announcements = \App\Models\Announcement::where(function($q) {
            $q->where('target_role', 'student')
              ->orWhereNull('target_role');
        })
        ->active()
        ->orderBy('is_pinned', 'desc')
        ->orderBy('published_at', 'desc')
        ->take(10)
        ->get();

        return view('student.dashboard', [
            'profile' => $profile,
            'recentGrades' => $recentGrades,
            'totalGrades' => $grades->count(),
            'averageGrade' => $grades->avg('final_grade') ?? 0,
            'announcements' => $announcements,
        ]);
    }

    private function teacherDashboard($user): View
    {
        // Get all class schedules with related data (matching the pattern from TeacherManagementController)
        $classSchedules = $user->classSchedules()
            ->with([
                'section.schoolYear', 
                'section.strand',
                'subject', 
                'academicPeriod',
                'enrollments' => function($query) {
                    $query->where('status', 'enrolled');
                }
            ])
            ->get();

        // Calculate total enrolled students from the eager-loaded enrollments
        $totalStudents = $classSchedules->sum(function($schedule) {
            return $schedule->enrollments->count();
        });

        // Get class schedule IDs for queries (only if there are schedules)
        $scheduleIds = $classSchedules->pluck('id');
        
        // Count pending grade submissions (Draft status)
        $pendingGrades = 0;
        $submittedGrades = 0;
        $totalAssessments = 0;
        $recentGradeSubmissions = collect();

        if ($scheduleIds->isNotEmpty()) {
            $pendingGrades = \App\Models\QuarterlyGrade::whereIn('class_schedule_id', $scheduleIds)
                ->where('status', 'Draft')
                ->count();

            // Count submitted grades waiting for approval
            $submittedGrades = \App\Models\QuarterlyGrade::whereIn('class_schedule_id', $scheduleIds)
                ->where('status', 'Submitted')
                ->count();

            // Count total assessments created
            $totalAssessments = \App\Models\Assessment::whereIn('class_schedule_id', $scheduleIds)
                ->count();

            // Get recent activity (recent grade submissions)
            $recentGradeSubmissions = \App\Models\QuarterlyGrade::whereIn('class_schedule_id', $scheduleIds)
                ->with(['student', 'classSchedule.subject'])
                ->latest('updated_at')
                ->take(5)
                ->get();
        }

        // Recent announcements for teachers
        $announcements = \App\Models\Announcement::where(function($q) {
            $q->where('target_role', 'teacher')
              ->orWhereNull('target_role');
        })
        ->where('published_at', '<=', now())
        ->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        })
        ->latest('published_at')
        ->take(5)
        ->get();

        return view('teacher.dashboard', [
            'classSchedules' => $classSchedules,
            'totalClasses' => $classSchedules->count(),
            'totalStudents' => $totalStudents,
            'pendingGrades' => $pendingGrades,
            'submittedGrades' => $submittedGrades,
            'totalAssessments' => $totalAssessments,
            'recentGradeSubmissions' => $recentGradeSubmissions,
            'announcements' => $announcements,
        ]);
    }

    private function adminDashboard($user): View
    {
        $data = [];

        // Basic counts for all admins
        $data['totalUsers'] = User::count();
        $data['totalStudents'] = StudentProfile::count();
        $data['totalTeachers'] = User::where('role', 'teacher')->count();
        $data['totalAdmins'] = User::whereIn('role', ['super_admin', 'academic_admin', 'registrar_admin', 'technical_admin'])->count();

        // Pending grade approvals (Registrar & Super Admin)
        if ($user->role === 'registrar_admin' || $user->isSuperAdmin()) {
            $data['pendingGrades'] = QuarterlyGrade::where('status', 'Submitted')->count();
            $data['approvedGrades'] = QuarterlyGrade::where('status', 'Approved')->count();
            $data['pendingDocuments'] = \App\Models\DocumentRequest::where('status', 'Pending')->count();
        }

        // Attendance today (Academic & Super Admin)
        if ($user->role === 'academic_admin' || $user->isSuperAdmin()) {
            $todayAttendance = Attendance::whereDate('date', today())->count();
            $totalExpected = StudentProfile::whereHas('currentSection')->count();
            $data['attendanceToday'] = $totalExpected > 0 ? round(($todayAttendance / $totalExpected) * 100, 1) : 0;
            $data['totalSections'] = \App\Models\Section::count();
        }

        // System status (Technical & Super Admin)
        if ($user->role === 'technical_admin' || $user->isSuperAdmin()) {
            $backupDir = storage_path('app/backups');
            $backupFiles = collect(file_exists($backupDir) ? array_merge(glob($backupDir . '/*.sql') ?: [], glob($backupDir . '/*.sqlite') ?: []) : []);
                
            if ($backupFiles->isNotEmpty()) {
                $lastBackup = $backupFiles->map(function($file) {
                    return filemtime($file);
                })->max();
                $data['lastBackup'] = \Carbon\Carbon::createFromTimestamp($lastBackup);
            } else {
                $data['lastBackup'] = null;
            }
            $data['systemStatus'] = 'operational'; // You can expand this with actual health checks
            
            // Additional stats for technical admin
            $data['totalBackups'] = $backupFiles->count();
            $data['activityLogsToday'] = \App\Models\ActivityLog::whereDate('created_at', today())->count();
            $data['totalSystemUsers'] = User::count();
        }

        // Recent announcements for all admins
        $data['announcements'] = \App\Models\Announcement::where(function($q) use ($user) {
            $q->where('target_role', 'admin')
              ->orWhereNull('target_role');
        })
        ->where('published_at', '<=', now())
        ->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        })
        ->latest('published_at')
        ->take(5)
        ->get();

        // Action items based on role
        if ($user->role === 'registrar_admin') {
            $data['actionItems']['grades'] = QuarterlyGrade::with(['student', 'classSchedule.subject'])
                ->where('status', 'Submitted')
                ->latest()
                ->take(5)
                ->get();
        }

        if ($user->role === 'academic_admin') {
            // Unassigned class schedules
            $data['actionItems']['unassigned'] = \App\Models\ClassSchedule::whereNull('teacher_id')
                ->with('subject', 'section')
                ->take(5)
                ->get();
        }

        // Analytics data (enrollment by strand)
        $data['enrollmentByStrand'] = \App\Models\Strand::withCount('studentProfiles')
            ->orderBy('student_profiles_count', 'desc')
            ->get();

        return view('admin.dashboard', $data);
    }
}
