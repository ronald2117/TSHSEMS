<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\View\View;

class AttendanceViewController extends Controller
{
    public function index(): View
    {
        $attendances = Attendance::where('student_id', auth()->id())
            ->with('classSchedule.subject')
            ->orderByDesc('date')
            ->paginate(30);

        $summary = [
            'present' => Attendance::where('student_id', auth()->id())->where('status', 'Present')->count(),
            'absent' => Attendance::where('student_id', auth()->id())->where('status', 'Absent')->count(),
            'late' => Attendance::where('student_id', auth()->id())->where('status', 'Late')->count(),
            'excused' => Attendance::where('student_id', auth()->id())->where('status', 'Excused')->count(),
        ];

        return view('student.attendance.index', [
            'attendances' => $attendances,
            'summary' => $summary,
        ]);
    }
}
