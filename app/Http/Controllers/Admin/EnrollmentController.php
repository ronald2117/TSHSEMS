<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentProfile;
use App\Models\Section;
use App\Models\StudentSubjectEnrollment;
use App\Models\ClassSchedule;
use App\Models\StudentEnrollmentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    /**
     * Display enrollment overview
     */
    public function index(Request $request)
    {
        $schoolYearId = $request->get('school_year_id');
        
        // Get enrollment statistics
        $stats = [
            'total_enrolled' => StudentProfile::whereNotNull('current_section_id')->count(),
            'not_enrolled' => StudentProfile::whereNull('current_section_id')->count(),
            'grade_11' => StudentProfile::where('grade_level', 11)->whereNotNull('current_section_id')->count(),
            'grade_12' => StudentProfile::where('grade_level', 12)->whereNotNull('current_section_id')->count(),
        ];

        // Get sections with enrollment counts
        $sections = Section::with(['strand', 'schoolYear'])
            ->withCount('students')
            ->when($schoolYearId, function ($query, $schoolYearId) {
                $query->where('school_year_id', $schoolYearId);
            })
            ->latest()
            ->get();

        return view('admin.registrar-admin.enrollment.index', compact('stats', 'sections'));
    }

    /**
     * Show enrollment form for a student
     */
    public function enroll($studentId)
    {
        $student = StudentProfile::with(['user', 'currentSection'])->findOrFail($studentId);
        $sections = Section::with(['strand', 'schoolYear'])
            ->where('school_year_id', function ($query) {
                $query->select('id')
                    ->from('school_years')
                    ->where('is_active', true)
                    ->limit(1);
            })
            ->get();

        return view('admin.registrar-admin.enrollment.enroll', compact('student', 'sections'));
    }

    /**
     * Process student enrollment
     */
    public function processEnrollment(Request $request, $studentId)
    {
        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'enrollment_date' => 'required|date',
        ]);

        $student = StudentProfile::findOrFail($studentId);
        $section = Section::with('schoolYear')->findOrFail($validated['section_id']);

        DB::beginTransaction();
        try {
            // Update student's current section
            $student->update([
                'current_section_id' => $section->id,
                'grade_level' => $section->grade_level,
            ]);

            // Create enrollment history record
            StudentEnrollmentHistory::create([
                'student_id' => $student->id,
                'section_id' => $section->id,
                'school_year_id' => $section->school_year_id,
                'enrollment_date' => $validated['enrollment_date'],
                'status' => 'enrolled',
            ]);

            // Enroll student in all subjects for this section
            $classSchedules = ClassSchedule::where('section_id', $section->id)->get();
            
            foreach ($classSchedules as $schedule) {
                StudentSubjectEnrollment::firstOrCreate([
                    'student_id' => $student->id,
                    'class_schedule_id' => $schedule->id,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.students.show', $student->id)
                ->with('success', 'Student enrolled successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Enrollment failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Show enrollment history
     */
    public function history(Request $request)
    {
        $schoolYearId = $request->get('school_year_id');
        
        $enrollments = StudentEnrollmentHistory::with(['student.user', 'section.strand', 'schoolYear'])
            ->when($schoolYearId, function ($query, $schoolYearId) {
                $query->where('school_year_id', $schoolYearId);
            })
            ->latest('enrollment_date')
            ->paginate(50);

        return view('admin.registrar-admin.enrollment.history', compact('enrollments'));
    }
}
