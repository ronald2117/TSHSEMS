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

        // Get grading weights for this subject type
        $weights = GradingComponent::where('subject_type', $grading->subject->type ?? 'core')->first();
        $gradingWeights = [
            'written' => $weights->written_weight ?? 0.25,
            'performance' => $weights->performance_weight ?? 0.50,
            'exam' => $weights->exam_weight ?? 0.25,
        ];

        return view('teacher.grading.show', [
            'classSchedule' => $grading,
            'students' => $students,
            'assessments' => $assessments,
            'grades' => $grades,
            'gradingWeights' => $gradingWeights,
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

    public function submitGrade(ClassSchedule $classSchedule, $studentId, Request $request): RedirectResponse
    {
        $this->authorize('view', $classSchedule);

        $quarter = $request->input('quarter', 1);

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

        return back()->with('success', 'Grade submitted for approval.');
    }

    public function unsubmitGrade(QuarterlyGrade $grade): RedirectResponse
    {
        // Only allow unsubmitting if status is Submitted (not Approved)
        if ($grade->status !== 'Submitted') {
            return back()->with('error', 'Cannot unsubmit this grade.');
        }

        $grade->update([
            'status' => 'Draft',
            'submitted_at' => null,
            'submitted_by' => null,
        ]);

        return back()->with('success', 'Grade submission withdrawn.');
    }

    private function calculateInitialGrade($classSchedule, $studentId, $quarter)
    {
        $assessments = $classSchedule->assessments()
            ->where('quarter', $quarter)
            ->get()
            ->groupBy('type');

        // Get grading weights, use defaults if not found
        $weights = GradingComponent::where('subject_type', $classSchedule->subject->type ?? 'core')->first();
        
        // Default weights if no grading component is set
        $writtenWeight = $weights->written_weight ?? 0.25;
        $performanceWeight = $weights->performance_weight ?? 0.50;
        $examWeight = $weights->exam_weight ?? 0.25;

        // Calculate written work component
        $writtenAssessments = $assessments->get('written_work', collect());
        $writtenScores = [];
        foreach ($writtenAssessments as $assessment) {
            $score = $assessment->scores()->where('student_id', $studentId)->first();
            if ($score && $assessment->max_score > 0) {
                $writtenScores[] = ($score->score / $assessment->max_score) * 100;
            }
        }
        $written = count($writtenScores) > 0 ? array_sum($writtenScores) / count($writtenScores) : 0;

        // Calculate performance task component
        $performanceAssessments = $assessments->get('performance_task', collect());
        $performanceScores = [];
        foreach ($performanceAssessments as $assessment) {
            $score = $assessment->scores()->where('student_id', $studentId)->first();
            if ($score && $assessment->max_score > 0) {
                $performanceScores[] = ($score->score / $assessment->max_score) * 100;
            }
        }
        $performance = count($performanceScores) > 0 ? array_sum($performanceScores) / count($performanceScores) : 0;

        // Calculate quarterly assessment component
        $examAssessments = $assessments->get('quarterly_assessment', collect());
        $examScores = [];
        foreach ($examAssessments as $assessment) {
            $score = $assessment->scores()->where('student_id', $studentId)->first();
            if ($score && $assessment->max_score > 0) {
                $examScores[] = ($score->score / $assessment->max_score) * 100;
            }
        }
        $exam = count($examScores) > 0 ? array_sum($examScores) / count($examScores) : 0;

        // Calculate weighted initial grade
        $initialGrade = ($written * $writtenWeight) +
                       ($performance * $performanceWeight) +
                       ($exam * $examWeight);

        // Ensure grade is within valid range
        return max(0, min(100, round($initialGrade, 2)));
    }
}
