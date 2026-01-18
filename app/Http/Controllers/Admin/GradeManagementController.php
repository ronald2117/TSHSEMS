<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuarterlyGrade;
use App\Models\GradeAuditLog;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GradeManagementController extends Controller
{
    public function index(): View
    {
        $grades = QuarterlyGrade::with('student', 'classSchedule.subject')
            ->where('status', '!=', 'Approved')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.grades.index', ['grades' => $grades]);
    }

    public function show(QuarterlyGrade $quarterlyGrade): View
    {
        $quarterlyGrade->load([
            'student' => function ($query) {
                $query->withTrashed();
            },
            'classSchedule' => function ($query) {
                $query->withTrashed()->with(['subject' => function ($q) {
                    $q->withTrashed();
                }]);
            },
            'auditLogs'
        ]);

        return view('admin.grades.show', ['grade' => $quarterlyGrade]);
    }

    public function approve(QuarterlyGrade $quarterlyGrade): RedirectResponse
    {
        $quarterlyGrade->update([
            'status' => 'Approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        GradeAuditLog::create([
            'quarterly_grade_id' => $quarterlyGrade->id,
            'user_id' => auth()->id(),
            'old_grade' => null,
            'new_grade' => $quarterlyGrade->final_grade,
            'field_changed' => 'status',
            'reason' => 'Grade approved by registrar',
            'ip_address' => request()->ip(),
        ]);

        ActivityLog::log(
            'approve',
            "Approved grade for student: {$quarterlyGrade->student->user->full_name} - {$quarterlyGrade->classSchedule->subject->name} (Quarter {$quarterlyGrade->quarter})"
        );

        return back()->with('success', 'Grade approved.');
    }

    public function return(QuarterlyGrade $quarterlyGrade, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'return_reason' => 'required|string|max:500',
        ]);

        $quarterlyGrade->update([
            'status' => 'Returned',
            'return_reason' => $validated['return_reason'],
        ]);

        GradeAuditLog::create([
            'quarterly_grade_id' => $quarterlyGrade->id,
            'user_id' => auth()->id(),
            'old_grade' => null,
            'new_grade' => $quarterlyGrade->final_grade,
            'field_changed' => 'status',
            'reason' => 'Grade returned: ' . $validated['return_reason'],
            'ip_address' => request()->ip(),
        ]);

        ActivityLog::log(
            'return',
            "Returned grade for student: {$quarterlyGrade->student->user->full_name} - {$quarterlyGrade->classSchedule->subject->name} (Reason: {$validated['return_reason']})"
        );

        return back()->with('success', 'Grade returned to teacher.');
    }

    public function override(QuarterlyGrade $quarterlyGrade, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'new_grade' => 'required|integer|min:60|max:100',
            'reason' => 'required|string|max:500',
        ]);

        $oldGrade = $quarterlyGrade->final_grade;

        $quarterlyGrade->update([
            'final_grade' => $validated['new_grade'],
            'remarks' => $validated['new_grade'] >= 75 ? 'Passed' : 'Failed',
            'status' => 'Approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        GradeAuditLog::create([
            'quarterly_grade_id' => $quarterlyGrade->id,
            'user_id' => auth()->id(),
            'old_grade' => $oldGrade,
            'new_grade' => $validated['new_grade'],
            'field_changed' => 'final_grade',
            'reason' => 'Grade override: ' . $validated['reason'],
            'ip_address' => request()->ip(),
        ]);
        ActivityLog::log(
            'override',
            "Overrode grade for student: {$quarterlyGrade->student->user->full_name} - {$quarterlyGrade->classSchedule->subject->name} (Old: {$oldGrade} → New: {$validated['new_grade']})"
        );
        return back()->with('success', 'Grade overridden and approved.');
    }
}
