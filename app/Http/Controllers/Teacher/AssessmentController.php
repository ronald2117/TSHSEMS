<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\ClassSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssessmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Assessment::with(['classSchedule.section', 'classSchedule.subject'])
            ->whereHas('classSchedule', function ($q) {
                $q->where('teacher_id', Auth::id());
            });

        if ($request->filled('class_schedule_id')) {
            $query->where('class_schedule_id', $request->class_schedule_id);
        }

        if ($request->filled('quarter')) {
            $query->where('quarter', $request->quarter);
        }

        $assessments = $query->orderBy('assessment_date', 'desc')
            ->paginate(15);

        $classSchedules = ClassSchedule::where('teacher_id', Auth::id())
            ->with(['section', 'subject'])
            ->get();

        return view('teacher.assessments.index', compact('assessments', 'classSchedules'));
    }

    public function create(Request $request)
    {
        $classScheduleId = $request->query('class_schedule_id');
        
        $classSchedules = ClassSchedule::where('teacher_id', Auth::id())
            ->with(['section', 'subject'])
            ->get();

        return view('teacher.assessments.create', compact('classSchedules', 'classScheduleId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_schedule_id' => 'required|exists:class_schedules,id',
            'title' => 'required|string|max:255',
            'type' => 'required|in:written_work,performance_task,quarterly_assessment',
            'max_score' => 'required|integer|min:1',
            'quarter' => 'required|integer|min:1|max:4',
            'assessment_date' => 'required|date',
            'is_published' => 'boolean',
        ]);

        // Verify the class schedule belongs to this teacher
        $classSchedule = ClassSchedule::findOrFail($validated['class_schedule_id']);
        if ($classSchedule->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this class schedule.');
        }

        $validated['is_published'] = $request->has('is_published');

        Assessment::create($validated);

        return redirect()
            ->route('teacher.assessments.index', ['class_schedule_id' => $validated['class_schedule_id']])
            ->with('success', 'Assessment created successfully!');
    }

    public function show(Assessment $assessment)
    {
        // Verify ownership
        if ($assessment->classSchedule->teacher_id !== Auth::id()) {
            abort(403);
        }

        $assessment->load(['classSchedule.section', 'classSchedule.subject', 'scores.student']);
        
        return view('teacher.assessments.show', compact('assessment'));
    }

    public function edit(Assessment $assessment)
    {
        // Verify ownership
        if ($assessment->classSchedule->teacher_id !== Auth::id()) {
            abort(403);
        }

        $classSchedules = ClassSchedule::where('teacher_id', Auth::id())
            ->with(['section', 'subject'])
            ->get();

        return view('teacher.assessments.edit', compact('assessment', 'classSchedules'));
    }

    public function update(Request $request, Assessment $assessment)
    {
        // Verify ownership
        if ($assessment->classSchedule->teacher_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'class_schedule_id' => 'required|exists:class_schedules,id',
            'title' => 'required|string|max:255',
            'type' => 'required|in:written_work,performance_task,quarterly_assessment',
            'max_score' => 'required|integer|min:1',
            'quarter' => 'required|integer|min:1|max:4',
            'assessment_date' => 'required|date',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->has('is_published');

        $assessment->update($validated);

        return redirect()
            ->route('teacher.assessments.index')
            ->with('success', 'Assessment updated successfully!');
    }

    public function destroy(Assessment $assessment)
    {
        // Verify ownership
        if ($assessment->classSchedule->teacher_id !== Auth::id()) {
            abort(403);
        }

        $assessment->delete();

        return redirect()
            ->route('teacher.assessments.index')
            ->with('success', 'Assessment deleted successfully!');
    }

    public function togglePublish(Assessment $assessment)
    {
        // Verify ownership
        if ($assessment->classSchedule->teacher_id !== Auth::id()) {
            abort(403);
        }

        $assessment->update([
            'is_published' => !$assessment->is_published
        ]);

        $status = $assessment->is_published ? 'published' : 'unpublished';

        return redirect()
            ->back()
            ->with('success', "Assessment {$status} successfully!");
    }
}
