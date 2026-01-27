<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\Assessment;
use App\Models\StudentScore;
use App\Models\QuarterlyGrade;
use App\Models\GradingComponent;
use App\Models\GradeTransmutation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GradingController extends Controller
{
    use AuthorizesRequests;

    public function index(): View
    {
        $classSchedules = auth()->user()->classSchedules()
            ->with('section', 'subject', 'enrollments')
            ->get();

        return view('teacher.grading.index', ['classSchedules' => $classSchedules]);
    }

    public function show(ClassSchedule $grading): View
    {
        $this->authorize('view', $grading);

        $grading->load(['subject', 'section', 'academicPeriod', 'enrollments.student.studentProfile']);

        $students = $grading->enrollments
            ->where('status', 'enrolled')
            ->pluck('student')
            ->filter();

        $assessments = $grading->assessments()
            ->with('scores')
            ->get()
            ->groupBy('type');

        $grades = QuarterlyGrade::where('class_schedule_id', $grading->id)->get();

        return view('teacher.grading.show', [
            'classSchedule' => $grading,
            'students' => $students,
            'assessments' => $assessments,
            'grades' => $grades,
        ]);
    }

    public function edit(ClassSchedule $grading): View
    {
        $this->authorize('view', $grading);

        $grading->load(['subject', 'section', 'enrollments.student.studentProfile']);

        $students = $grading->enrollments
            ->where('status', 'enrolled')
            ->pluck('student')
            ->filter();

        $assessments = $grading->assessments()
            ->orderBy('quarter')
            ->orderBy('type')
            ->get()
            ->groupBy('type');

        $scores = StudentScore::whereIn('assessment_id', $grading->assessments()->pluck('id'))
            ->get()
            ->groupBy('student_id');

        return view('teacher.grading.edit', [
            'classSchedule' => $grading,
            'students' => $students,
            'assessments' => $assessments,
            'scores' => $scores,
        ]);
    }

    public function update(Request $request, ClassSchedule $grading): RedirectResponse
    {
        $this->authorize('view', $grading);

        $validated = $request->validate([
            'scores' => 'required|array',
            'scores.*.*' => 'nullable|numeric|min:0',
        ]);

        foreach ($validated['scores'] as $studentId => $assessmentScores) {
            foreach ($assessmentScores as $assessmentId => $score) {
                if ($score !== null) {
                    StudentScore::updateOrCreate(
                        [
                            'assessment_id' => $assessmentId,
                            'student_id' => $studentId,
                        ],
                        ['score' => $score]
                    );
                }
            }
        }

        return back()->with('success', 'Scores saved successfully.');
    }

    public function submitGrades(ClassSchedule $classSchedule, Request $request): RedirectResponse
    {
        $this->authorize('view', $classSchedule);

        $quarter = $request->query('quarter', 1);

        // Get all enrolled students
        $students = $classSchedule->enrollments()
            ->pluck('student_id');

        foreach ($students as $studentId) {
            // Calculate grade
            $initialGrade = $this->calculateInitialGrade($classSchedule, $studentId, $quarter);
            $finalGrade = GradeTransmutation::transmute($initialGrade);
            $remarks = GradeTransmutation::getRemarks($finalGrade);

            QuarterlyGrade::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'class_schedule_id' => $classSchedule->id,
                    'quarter' => $quarter,
                ],
                [
                    'initial_grade' => $initialGrade,
                    'final_grade' => $finalGrade,
                    'remarks' => $remarks,
                    'status' => 'Submitted',
                    'submitted_at' => now(),
                    'submitted_by' => auth()->id(),
                ]
            );
        }

        return redirect()->route('teacher.grading.show', $classSchedule)
            ->with('success', 'Grades submitted for approval.');
    }

    private function calculateInitialGrade($classSchedule, $studentId, $quarter)
    {
        $assessments = $classSchedule->assessments()
            ->where('quarter', $quarter)
            ->get()
            ->groupBy('type');

        $weights = GradingComponent::where('subject_type', $classSchedule->subject->type)->first();

        $written = $assessments->get('written_work', collect())
            ->avg(fn($a) => $a->scores()->where('student_id', $studentId)->avg('score'));

        $performance = $assessments->get('performance_task', collect())
            ->avg(fn($a) => $a->scores()->where('student_id', $studentId)->avg('score'));

        $exam = $assessments->get('quarterly_assessment', collect())
            ->sum(fn($a) => $a->scores()->where('student_id', $studentId)->avg('score'));

        return ($written * $weights->written_weight) +
               ($performance * $weights->performance_weight) +
               ($exam * $weights->exam_weight);
    }
}
