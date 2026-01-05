<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\Section;
use App\Models\Subject;
use App\Models\User;
use App\Models\AcademicPeriod;
use App\Models\SchoolYear;
use Illuminate\Http\Request;

class ClassSchedulesController extends Controller
{
    public function index(Request $request)
    {
        $query = ClassSchedule::with(['section.strand', 'subject', 'teacher', 'academicPeriod.schoolYear'])
            ->latest();

        // Filter by school year
        if ($request->filled('school_year_id')) {
            $query->whereHas('academicPeriod', function ($q) use ($request) {
                $q->where('school_year_id', $request->school_year_id);
            });
        }

        // Filter by section
        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        // Filter by teacher
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        $schedules = $query->paginate(15);
        $schoolYears = SchoolYear::orderBy('name', 'desc')->get();
        $sections = Section::with('strand')->orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('first_name')->get();

        return view('admin.class-schedules.index', compact('schedules', 'schoolYears', 'sections', 'teachers'));
    }

    public function create()
    {
        $schoolYears = SchoolYear::orderBy('name', 'desc')->get();
        $sections = Section::with('strand', 'schoolYear')->orderBy('name')->get();
        $subjects = Subject::orderBy('code')->get();
        $teachers = User::where('role', 'teacher')->orderBy('first_name')->get();
        
        return view('admin.class-schedules.create', compact('schoolYears', 'sections', 'subjects', 'teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'academic_period_id' => 'required|exists:academic_periods,id',
            'schedule_time' => 'nullable|string|max:100',
            'room' => 'nullable|string|max:50',
            'schedule_details' => 'nullable|array',
        ]);

        // Check for duplicate schedule
        $exists = ClassSchedule::where('section_id', $validated['section_id'])
            ->where('subject_id', $validated['subject_id'])
            ->where('academic_period_id', $validated['academic_period_id'])
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'This subject is already scheduled for this section in the selected period.');
        }

        ClassSchedule::create($validated);

        return redirect()->route('admin.class-schedules.index')
            ->with('success', 'Class schedule created successfully.');
    }

    public function show(ClassSchedule $classSchedule)
    {
        $classSchedule->load([
            'section.strand',
            'subject',
            'teacher.teacherProfile',
            'academicPeriod.schoolYear',
            'enrollments' => function ($query) {
                $query->where('status', 'enrolled')->with('student');
            }
        ]);

        return view('admin.class-schedules.show', compact('classSchedule'));
    }

    public function edit(ClassSchedule $classSchedule)
    {
        $schoolYears = SchoolYear::orderBy('name', 'desc')->get();
        $sections = Section::with('strand', 'schoolYear')->orderBy('name')->get();
        $subjects = Subject::orderBy('code')->get();
        $teachers = User::where('role', 'teacher')->orderBy('first_name')->get();
        $academicPeriods = AcademicPeriod::with('schoolYear')->orderBy('id', 'desc')->get();

        return view('admin.class-schedules.edit', compact('classSchedule', 'schoolYears', 'sections', 'subjects', 'teachers', 'academicPeriods'));
    }

    public function update(Request $request, ClassSchedule $classSchedule)
    {
        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'academic_period_id' => 'required|exists:academic_periods,id',
            'schedule_time' => 'nullable|string|max:100',
            'room' => 'nullable|string|max:50',
            'schedule_details' => 'nullable|array',
        ]);

        // Check for duplicate schedule (excluding current)
        $exists = ClassSchedule::where('section_id', $validated['section_id'])
            ->where('subject_id', $validated['subject_id'])
            ->where('academic_period_id', $validated['academic_period_id'])
            ->where('id', '!=', $classSchedule->id)
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'This subject is already scheduled for this section in the selected period.');
        }

        $classSchedule->update($validated);

        return redirect()->route('admin.class-schedules.show', $classSchedule)
            ->with('success', 'Class schedule updated successfully.');
    }

    public function destroy(ClassSchedule $classSchedule)
    {
        if ($classSchedule->enrollments()->count() > 0) {
            return redirect()->route('admin.class-schedules.index')
                ->with('error', 'Cannot delete class schedule with enrolled students.');
        }

        $classSchedule->delete();

        return redirect()->route('admin.class-schedules.index')
            ->with('success', 'Class schedule deleted successfully.');
    }

    // AJAX endpoint to get academic periods by school year
    public function getAcademicPeriods(Request $request)
    {
        $periods = AcademicPeriod::where('school_year_id', $request->school_year_id)
            ->orderBy('name')
            ->get();

        return response()->json($periods);
    }
}
