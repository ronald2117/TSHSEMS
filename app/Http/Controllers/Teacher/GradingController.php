<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\Assessment;
use App\Models\StudentScore;
use App\Models\QuarterlyGrade;
use App\Models\GradingComponent;
use App\Models\GradeTransmutation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GradingController extends Controller
{
    public function index(): View
    {
        $classSchedules = auth()->user()->classSchedules()
            ->with('section', 'subject', 'enrollments')
            ->get();

        return view('teacher.grading.index', ['classSchedules' => $classSchedules]);
    }

    public function show(ClassSchedule $classSchedule): View
    {
        $this->authorize('view', $classSchedule);

        $students = $classSchedule->enrollments()
            ->with('user')
            ->get()
            ->map(fn($e) => $e->user);

        $assessments = $classSchedule->assessments()
            ->where('is_published', true)
            ->get()
            ->groupBy('type');

        $grades = QuarterlyGrade::where('class_schedule_id', $classSchedule->id)->get();

        return view('teacher.grading.show', [
            'classSchedule' => $classSchedule,
            'students' => $students,
            'assessments' => $assessments,
            'grades' => $grades,
        ]);
    }

    public function edit(ClassSchedule $classSchedule): View
    {
        $this->authorize('view', $classSchedule);

        $students = $classSchedule->enrollments()
            ->with('user')
            ->get()
            ->map(fn($e) => $e->user);

        $assessments = $classSchedule->assessments()
            ->orderBy('type')
            ->get()
            ->groupBy('type');

        $scores = StudentScore::whereIn('assessment_id', $classSchedule->assessments()->pluck('id'))
            ->get()
            ->groupBy('student_id');

        return view('teacher.grading.edit', [
            'classSchedule' => $classSchedule,
            'students' => $students,
            'assessments' => $assessments,
            'scores' => $scores,
        ]);
    }

    public function update(Request $request, ClassSchedule $classSchedule): RedirectResponse
    {
        $this->authorize('view', $classSchedule);

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
