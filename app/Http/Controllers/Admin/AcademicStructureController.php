<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolYear;
use App\Models\AcademicPeriod;
use App\Models\Section;
use App\Models\Strand;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AcademicStructureController extends Controller
{
    // School Years
    public function indexSchoolYears(): View
    {
        $schoolYears = SchoolYear::orderByDesc('start_date')->paginate(20);
        return view('admin.school-years.index', ['schoolYears' => $schoolYears]);
    }

    public function createSchoolYear(): View
    {
        return view('admin.school-years.create');
    }

    public function storeSchoolYear(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:school_years',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        SchoolYear::create($validated);

        return redirect()->route('admin.school-years.index')->with('success', 'School year created.');
    }

    public function activate(SchoolYear $schoolYear): RedirectResponse
    {
        SchoolYear::where('is_active', true)->update(['is_active' => false]);
        $schoolYear->update(['is_active' => true]);

        return back()->with('success', 'School year activated.');
    }

    // Sections
    public function indexSections(): View
    {
        $sections = Section::with('strand', 'schoolYear', 'adviser')
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('admin.sections.index', ['sections' => $sections]);
    }

    public function createSection(): View
    {
        $schoolYears = SchoolYear::all();
        $strands = Strand::all();
        $teachers = User::where('role', 'teacher')->get();
        return view('admin.sections.create', compact('schoolYears', 'strands', 'teachers'));
    }

    public function storeSection(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_year_id' => 'required|exists:school_years,id',
            'name' => 'required|string',
            'grade_level' => 'required|in:11,12',
            'strand_id' => 'required|exists:strands,id',
            'adviser_id' => 'nullable|exists:users,id',
            'max_students' => 'required|integer|min:1',
        ]);

        Section::create($validated);

        return redirect()->route('admin.sections.index')->with('success', 'Section created.');
    }

    public function editSection(Section $section): View
    {
        $schoolYears = SchoolYear::all();
        $strands = Strand::all();
        $teachers = User::where('role', 'teacher')->get();
        return view('admin.sections.edit', compact('section', 'schoolYears', 'strands', 'teachers'));
    }

    public function updateSection(Request $request, Section $section): RedirectResponse
    {
        $validated = $request->validate([
            'school_year_id' => 'required|exists:school_years,id',
            'name' => 'required|string',
            'grade_level' => 'required|in:11,12',
            'strand_id' => 'required|exists:strands,id',
            'adviser_id' => 'nullable|exists:users,id',
            'max_students' => 'required|integer|min:1',
        ]);

        $section->update($validated);

        return redirect()->route('admin.sections.index')->with('success', 'Section updated.');
    }

    public function destroySection(Section $section): RedirectResponse
    {
        $section->delete();

        return redirect()->route('admin.sections.index')->with('success', 'Section deleted.');
    }

    // Strands
    public function indexStrands(): View
    {
        $strands = Strand::with('track')->orderBy('code')->paginate(20);
        return view('admin.strands.index', ['strands' => $strands]);
    }

    // Subjects
    public function indexSubjects(): View
    {
        $subjects = Subject::orderBy('code')->paginate(20);
        return view('admin.subjects.index', ['subjects' => $subjects]);
    }

    public function createSubject(): View
    {
        return view('admin.subjects.create');
    }

    public function storeSubject(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:subjects',
            'name' => 'required|string',
            'type' => 'required|in:Core,Applied,Specialized',
            'units' => 'required|integer|min:1',
        ]);

        Subject::create($validated);

        return redirect()->route('admin.subjects.index')->with('success', 'Subject created.');
    }

    public function editSubject(Subject $subject): View
    {
        return view('admin.subjects.edit', compact('subject'));
    }

    public function updateSubject(Request $request, Subject $subject): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:subjects,code,' . $subject->id,
            'name' => 'required|string',
            'type' => 'required|in:Core,Applied,Specialized',
            'units' => 'required|integer|min:1',
        ]);

        $subject->update($validated);

        return redirect()->route('admin.subjects.index')->with('success', 'Subject updated.');
    }

    public function destroySubject(Subject $subject): RedirectResponse
    {
        $subject->delete();

        return redirect()->route('admin.subjects.index')->with('success', 'Subject deleted.');
    }

    // Generic Resource Methods
    public function index(): View
    {
        return $this->indexSchoolYears();
    }

    public function create(): View
    {
        return $this->createSchoolYear();
    }

    public function store(Request $request): RedirectResponse
    {
        return $this->storeSchoolYear($request);
    }

    public function edit($id): View
    {
        $schoolYear = SchoolYear::findOrFail($id);
        return view('admin.school-years.edit', ['schoolYear' => $schoolYear]);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $schoolYear = SchoolYear::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|unique:school_years,name,' . $id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $schoolYear->update($validated);

        return redirect()->route('admin.school-years.index')->with('success', 'School year updated.');
    }

    public function destroy($id): RedirectResponse
    {
        SchoolYear::findOrFail($id)->delete();

        return redirect()->route('admin.school-years.index')->with('success', 'School year deleted.');
    }
}
