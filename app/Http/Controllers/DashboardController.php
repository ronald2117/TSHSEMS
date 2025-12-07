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
        $totalUsers = User::count();
        $totalStudents = User::where('role', 'student')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalAdmins = User::whereIn('role', ['super_admin', 'academic_admin', 'registrar_admin', 'technical_admin'])->count();

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalStudents' => $totalStudents,
            'totalTeachers' => $totalTeachers,
            'totalAdmins' => $totalAdmins,
        ]);
    }
}
