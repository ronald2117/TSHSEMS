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
            ->with('section', 'subject', 'enrollments')
            ->get();

        return view('teacher.attendance.index', ['classSchedules' => $classSchedules]);
    }

    public function roster(ClassSchedule $classSchedule): View
    {
        $this->authorize('view', $classSchedule);

        $students = $classSchedule->enrollments()
            ->with('student.studentProfile')
            ->where('status', 'enrolled')
            ->get()
            ->pluck('student');

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
            'remarks' => 'nullable|array',
            'remarks.*' => 'nullable|string|max:255',
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
                        'remarks' => $validated['remarks'][$studentId] ?? null,
                        'recorded_by' => auth()->id(),
                    ]
                );
            }
        }

        return redirect()->route('teacher.attendance.index')
            ->with('success', 'Attendance recorded successfully.');
    }

    public function history(ClassSchedule $classSchedule, Request $request): View
    {
        $this->authorize('view', $classSchedule);

        // Get filter parameters
        $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $studentFilter = $request->input('student_id');

        // Get all enrolled students
        $students = $classSchedule->enrollments()
            ->with('student.studentProfile')
            ->where('status', 'enrolled')
            ->get()
            ->pluck('student')
            ->sortBy(function($student) {
                return $student->last_name ?? $student->email;
            });

        // Query attendance records
        $attendanceQuery = Attendance::where('class_schedule_id', $classSchedule->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->with(['student.studentProfile', 'recorder'])
            ->orderBy('date', 'desc')
            ->orderBy('student_id');

        if ($studentFilter) {
            $attendanceQuery->where('student_id', $studentFilter);
        }

        $attendances = $attendanceQuery->get();

        // Calculate statistics
        $stats = [
            'total_records' => $attendances->count(),
            'present' => $attendances->where('status', 'Present')->count(),
            'absent' => $attendances->where('status', 'Absent')->count(),
            'late' => $attendances->where('status', 'Late')->count(),
            'excused' => $attendances->where('status', 'Excused')->count(),
        ];

        // Group attendance by date for table view
        $attendanceByDate = $attendances->groupBy(function($attendance) {
            return $attendance->date->format('Y-m-d');
        });

        // Calculate per-student statistics
        $studentStats = [];
        foreach ($students as $student) {
            $studentAttendances = $attendances->where('student_id', $student->id);
            $studentStats[$student->id] = [
                'total' => $studentAttendances->count(),
                'present' => $studentAttendances->where('status', 'Present')->count(),
                'absent' => $studentAttendances->where('status', 'Absent')->count(),
                'late' => $studentAttendances->where('status', 'Late')->count(),
                'excused' => $studentAttendances->where('status', 'Excused')->count(),
                'attendance_rate' => $studentAttendances->count() > 0 
                    ? round(($studentAttendances->where('status', 'Present')->count() / $studentAttendances->count()) * 100, 1)
                    : 0,
            ];
        }

        return view('teacher.attendance.history', [
            'classSchedule' => $classSchedule,
            'students' => $students,
            'attendances' => $attendances,
            'attendanceByDate' => $attendanceByDate,
            'stats' => $stats,
            'studentStats' => $studentStats,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'studentFilter' => $studentFilter,
        ]);
    }

    public function monthlySummary(ClassSchedule $classSchedule, Request $request): View
    {
        $this->authorize('view', $classSchedule);

        // Get month parameter or default to current month
        $month = $request->input('month', now()->format('Y-m'));
        $monthDate = \Carbon\Carbon::parse($month . '-01');
        
        $startDate = $monthDate->copy()->startOfMonth()->format('Y-m-d');
        $endDate = $monthDate->copy()->endOfMonth()->format('Y-m-d');

        // Get all enrolled students
        $students = $classSchedule->enrollments()
            ->with('student.studentProfile')
            ->where('status', 'enrolled')
            ->get()
            ->pluck('student')
            ->sortBy(function($student) {
                return $student->last_name ?? $student->email;
            });

        // Get all attendance records for the month
        $attendances = Attendance::where('class_schedule_id', $classSchedule->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->with('student')
            ->orderBy('date')
            ->get();

        // Get unique dates in the month
        $dates = $attendances->pluck('date')->unique()->sort()->values();

        // Build attendance data per student
        $studentAttendanceData = [];
        foreach ($students as $student) {
            $studentRecords = $attendances->where('student_id', $student->id);
            
            $dateRecords = [];
            foreach ($dates as $date) {
                $record = $studentRecords->firstWhere('date', $date);
                $dateRecords[$date->format('Y-m-d')] = $record ? $record->status : null;
            }
            
            $studentAttendanceData[$student->id] = [
                'student' => $student,
                'records' => $dateRecords,
                'present' => $studentRecords->where('status', 'Present')->count(),
                'absent' => $studentRecords->where('status', 'Absent')->count(),
                'late' => $studentRecords->where('status', 'Late')->count(),
                'excused' => $studentRecords->where('status', 'Excused')->count(),
                'total' => $studentRecords->count(),
                'attendance_rate' => $studentRecords->count() > 0 
                    ? round(($studentRecords->where('status', 'Present')->count() / $studentRecords->count()) * 100, 1)
                    : 0,
            ];
        }

        // Overall statistics
        $stats = [
            'total_days' => $dates->count(),
            'total_records' => $attendances->count(),
            'present' => $attendances->where('status', 'Present')->count(),
            'absent' => $attendances->where('status', 'Absent')->count(),
            'late' => $attendances->where('status', 'Late')->count(),
            'excused' => $attendances->where('status', 'Excused')->count(),
        ];

        return view('teacher.attendance.monthly-summary', [
            'classSchedule' => $classSchedule,
            'students' => $students,
            'dates' => $dates,
            'studentAttendanceData' => $studentAttendanceData,
            'stats' => $stats,
            'month' => $month,
            'monthName' => $monthDate->format('F Y'),
        ]);
    }
}
