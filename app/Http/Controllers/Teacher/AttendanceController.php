<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\Attendance;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    use AuthorizesRequests;

    public function index(): View
    {
        $classSchedules = auth()->user()->classSchedules()
            ->with('section', 'subject')
            ->get();

        return view('teacher.attendance.index', ['classSchedules' => $classSchedules]);
    }

    public function roster(ClassSchedule $classSchedule): View
    {
        $this->authorize('view', $classSchedule);

        $students = $classSchedule->enrollments()
            ->with('user')
            ->get()
            ->map(fn($e) => $e->user);

        $attendances = Attendance::where('class_schedule_id', $classSchedule->id)
            ->whereDate('date', today())
            ->get()
            ->keyBy('student_id');

        return view('teacher.attendance.roster', [
            'classSchedule' => $classSchedule,
            'students' => $students,
            'attendances' => $attendances,
        ]);
    }

    public function create(): View
    {
        $classSchedules = auth()->user()->classSchedules()
            ->with('section', 'subject')
            ->get();

        return view('teacher.attendance.create', ['classSchedules' => $classSchedules]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'class_schedule_id' => 'required|exists:class_schedules,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*' => 'required|in:Present,Absent,Late,Excused',
        ]);

        $classSchedule = ClassSchedule::findOrFail($validated['class_schedule_id']);
        $this->authorize('view', $classSchedule);

        $students = $classSchedule->enrollments()->pluck('student_id');

        foreach ($students as $studentId) {
            if (isset($validated['attendance'][$studentId])) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'class_schedule_id' => $classSchedule->id,
                        'date' => $validated['date'],
                    ],
                    [
                        'status' => $validated['attendance'][$studentId],
                        'recorded_by' => auth()->id(),
                    ]
                );
            }
        }

        return redirect()->route('teacher.attendance.index')
            ->with('success', 'Attendance recorded successfully.');
    }
}
