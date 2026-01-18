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
        $profile = $user->studentProfile;
        $grades = QuarterlyGrade::where('student_id', $user->id)
            ->with('classSchedule.subject')
            ->where('status', 'Approved')
            ->get();
        
        $recentGrades = $grades->sortByDesc('created_at')->take(5);

        return view('student.dashboard', [
            'profile' => $profile,
            'recentGrades' => $recentGrades,
            'totalGrades' => $grades->count(),
            'averageGrade' => $grades->avg('final_grade'),
        ]);
    }

    private function teacherDashboard($user): View
    {
        $classSchedules = $user->classSchedules()
            ->with('section', 'subject')
            ->get();

        $totalStudents = $classSchedules->sum(fn($cs) => $cs->enrollments()->count());
        $pendingGrades = \App\Models\QuarterlyGrade::whereIn('class_schedule_id', $classSchedules->pluck('id'))
            ->where('status', 'Draft')
            ->count();

        return view('teacher.dashboard', [
            'classSchedules' => $classSchedules,
            'totalStudents' => $totalStudents,
            'pendingGrades' => $pendingGrades,
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
        }

        // Attendance today (Academic & Super Admin)
        if ($user->role === 'academic_admin' || $user->isSuperAdmin()) {
            $todayAttendance = Attendance::whereDate('date', today())->count();
            $totalExpected = StudentProfile::whereHas('currentSection')->count();
            $data['attendanceToday'] = $totalExpected > 0 ? round(($todayAttendance / $totalExpected) * 100, 1) : 0;
        }

        // System status (Technical & Super Admin)
        if ($user->role === 'technical_admin' || $user->isSuperAdmin()) {
            $backupFiles = \Storage::disk('local')->files('backups');
            if (!empty($backupFiles)) {
                $lastBackup = collect($backupFiles)->map(function($file) {
                    return \Storage::disk('local')->lastModified($file);
                })->max();
                $data['lastBackup'] = \Carbon\Carbon::createFromTimestamp($lastBackup);
            } else {
                $data['lastBackup'] = null;
            }
            $data['systemStatus'] = 'operational'; // You can expand this with actual health checks
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
        if ($user->role === 'registrar_admin' || $user->isSuperAdmin()) {
            $data['actionItems']['grades'] = QuarterlyGrade::with(['student', 'classSchedule.subject'])
                ->where('status', 'Submitted')
                ->latest()
                ->take(5)
                ->get();
        }

        if ($user->role === 'academic_admin' || $user->isSuperAdmin()) {
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
