<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\QuarterlyGrade;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;

class GradesController extends Controller
{
    use AuthorizesRequests;

    public function index(): View
    {
        $grades = QuarterlyGrade::where('student_id', auth()->id())
            ->where('status', 'Approved')
            ->with('classSchedule.subject', 'classSchedule.section')
            ->orderByDesc('created_at')
            ->paginate(20);

        $gwaByQuarter = $grades->groupBy('quarter')
            ->map(fn($group) => $group->avg('final_grade'));

        return view('student.grades.index', [
            'grades' => $grades,
            'gwaByQuarter' => $gwaByQuarter,
        ]);
    }

    public function show(QuarterlyGrade $grade): View
    {
        $this->authorize('view', $grade);

        $grade->load('classSchedule.subject', 'classSchedule.section', 'auditLogs');

        return view('student.grades.show', ['grade' => $grade]);
    }
}
