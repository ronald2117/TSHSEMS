<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\StudentProfile;
use App\Models\Section;
use App\Models\StudentSubjectEnrollment;
use App\Models\ClassSchedule;
use App\Models\StudentEnrollmentHistory;
use App\Models\User;
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
        $search = $request->get('search');
        
        // Get enrollment statistics
        $stats = [
            'total_enrolled' => StudentProfile::whereNotNull('current_section_id')->count(),
            'not_enrolled' => StudentProfile::whereNull('current_section_id')->count(),
            'grade_11' => StudentProfile::whereNotNull('current_section_id')
                ->whereHas('currentSection', function ($query) {
                    $query->where('grade_level', 11);
                })->count(),
            'grade_12' => StudentProfile::whereNotNull('current_section_id')
                ->whereHas('currentSection', function ($query) {
                    $query->where('grade_level', 12);
                })->count(),
        ];

        // Get sections with enrollment counts
        $sections = Section::with(['strand', 'schoolYear', 'studentProfiles'])
            ->when($schoolYearId, function ($query, $schoolYearId) {
                $query->where('school_year_id', $schoolYearId);
            })
            ->latest()
            ->get();

        // Get all students with their enrollment status
        // Only show students whose user accounts are not deleted
        $students = StudentProfile::with(['user', 'currentSection.strand'])
            ->whereHas('user', function ($query) {
                // This ensures the user exists and is not soft deleted
                $query->whereNull('deleted_at');
            })
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('lrn', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('admin.registrar-admin.enrollment.index', compact('stats', 'sections', 'students'));
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
        ]);

        $student = StudentProfile::findOrFail($studentId);
        $section = Section::with(['schoolYear.academicPeriods'])->findOrFail($validated['section_id']);

        // Get active academic period for this school year
        $academicPeriod = $section->schoolYear->academicPeriods()
            ->where('status', 'Active')
            ->first();

        if (!$academicPeriod) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'No active academic period found for this school year.']);
        }

        DB::beginTransaction();
        try {
            // Update student's current section
            $student->update([
                'current_section_id' => $section->id,
            ]);

            // Create enrollment history record
            // Note: student_enrollment_history.student_id references users.id
            StudentEnrollmentHistory::create([
                'student_id' => $student->user_id,
                'section_id' => $section->id,
                'academic_period_id' => $academicPeriod->id,
                'grade_level' => $section->grade_level,
                'status' => 'Enrolled',
            ]);

            // Remove any existing subject enrollments for this student (cleanup old data)
            // Note: student_subject_enrollments.student_id references users.id
            StudentSubjectEnrollment::where('student_id', $student->user_id)->forceDelete();

            // Enroll student in all subjects for this section
            $classSchedules = ClassSchedule::where('section_id', $section->id)->get();
            
            foreach ($classSchedules as $schedule) {
                StudentSubjectEnrollment::create([
                    'student_id' => $student->user_id,
                    'class_schedule_id' => $schedule->id,
                ]);
            }

            DB::commit();

            // Log activity
            ActivityLog::log(
                'student_enrolled',
                "Enrolled student {$student->user->full_name} to section {$section->name}"
            );

            return redirect()
                ->route('admin.enrollment.index')
                ->with('success', 'Student enrolled successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Enrollment failed: ' . $e->getMessage()]);
        }
    }

    /**     * Show transfer form for a student
     */
    public function showTransfer($studentId)
    {
        $student = StudentProfile::with(['user', 'currentSection.strand'])->findOrFail($studentId);
        
        if (!$student->current_section_id) {
            return redirect()->route('admin.enrollment.index')
                ->withErrors(['error' => 'Student is not currently enrolled in any section.']);
        }

        $sections = Section::with(['strand', 'schoolYear'])
            ->where('id', '!=', $student->current_section_id)
            ->where('school_year_id', function ($query) {
                $query->select('id')
                    ->from('school_years')
                    ->where('is_active', true)
                    ->limit(1);
            })
            ->get();

        return view('admin.registrar-admin.enrollment.transfer', compact('student', 'sections'));
    }

    /**
     * Process student transfer to another section
     */
    public function processTransfer(Request $request, $studentId)
    {
        $validated = $request->validate([
            'new_section_id' => 'required|exists:sections,id',
            'transfer_date' => 'required|date',
            'reason' => 'nullable|string|max:500',
        ]);

        $student = StudentProfile::findOrFail($studentId);
        $oldSection = Section::find($student->current_section_id);
        $newSection = Section::with(['schoolYear.academicPeriods'])->findOrFail($validated['new_section_id']);

        if ($student->current_section_id == $newSection->id) {
            return back()->withErrors(['error' => 'Student is already enrolled in this section.']);
        }

        // Get active academic period for this school year
        $academicPeriod = $newSection->schoolYear->academicPeriods()
            ->where('status', 'Active')
            ->first();

        if (!$academicPeriod) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'No active academic period found for this school year.']);
        }

        DB::beginTransaction();
        try {
            // Update old enrollment history to 'transferred'
            // Note: student_enrollment_history.student_id references users.id
            StudentEnrollmentHistory::where('student_id', $student->user_id)
                ->where('section_id', $oldSection->id)
                ->where('status', 'Enrolled')
                ->update(['status' => 'Transferred']);

            // Update student's current section
            $student->update([
                'current_section_id' => $newSection->id,
            ]);

            // Create new enrollment history record
            // Note: student_enrollment_history.student_id references users.id
            StudentEnrollmentHistory::create([
                'student_id' => $student->user_id,
                'section_id' => $newSection->id,
                'academic_period_id' => $academicPeriod->id,
                'grade_level' => $newSection->grade_level,
                'status' => 'Enrolled',
            ]);

            // Remove ALL old subject enrollments for this student (ensures clean state)
            // Note: student_subject_enrollments.student_id references users.id
            StudentSubjectEnrollment::where('student_id', $student->user_id)->delete();

            // Enroll student in all subjects for the new section
            $classSchedules = ClassSchedule::where('section_id', $newSection->id)->get();
            
            foreach ($classSchedules as $schedule) {
                StudentSubjectEnrollment::create([
                    'student_id' => $student->user_id,
                    'class_schedule_id' => $schedule->id,
                ]);
            }

            DB::commit();

            // Log activity
            ActivityLog::log(
                'student_transfer',
                "Transferred student {$student->user->full_name} from {$oldSection->name} to {$newSection->name}. Reason: " . ($validated['reason'] ?? 'N/A')
            );

            return redirect()
                ->route('admin.enrollment.index')
                ->with('success', "Student successfully transferred to {$newSection->name}");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Transfer failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Unenroll a student from their current section
     */
    public function unenroll($studentId)
    {
        $student = StudentProfile::with(['user', 'currentSection'])->findOrFail($studentId);

        if (!$student->current_section_id) {
            return redirect()->route('admin.enrollment.index')
                ->withErrors(['error' => 'Student is not currently enrolled in any section.']);
        }

        DB::beginTransaction();
        try {
            $section = $student->currentSection;
            $sectionId = $student->current_section_id;
            $sectionName = $section->name ?? 'Unknown Section (ID: ' . $sectionId . ')';

            // Update enrollment history to 'Withdrawn'
            // Also check for both 'Enrolled' and 'enrolled' to handle legacy data
            StudentEnrollmentHistory::where('student_id', $student->user_id)
                ->where('section_id', $sectionId)
                ->whereIn('status', ['Enrolled', 'enrolled'])
                ->update(['status' => 'Withdrawn']);

            // Remove subject enrollments (use forceDelete since soft deletes can cause issues)
            // Also use user_id since student_subject_enrollments.student_id references users.id
            // Handle case where section may have been deleted
            if ($section) {
                StudentSubjectEnrollment::where('student_id', $student->user_id)
                    ->whereHas('classSchedule', function ($query) use ($sectionId) {
                        $query->where('section_id', $sectionId);
                    })
                    ->forceDelete();
            } else {
                // If section doesn't exist, just remove all subject enrollments for this student
                // that reference non-existent class schedules
                StudentSubjectEnrollment::where('student_id', $student->user_id)->forceDelete();
            }

            // Update student's current section to null
            $student->update([
                'current_section_id' => null,
            ]);

            DB::commit();

            // Log activity
            ActivityLog::log(
                'student_unenrollment',
                "Unenrolled student {$student->user->full_name} from {$sectionName}"
            );

            return redirect()
                ->route('admin.enrollment.index')
                ->with('success', 'Student successfully unenrolled');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Unenrollment failed: ' . $e->getMessage()]);
        }
    }

    /**     * Show enrollment history
     */
    public function history(Request $request)
    {
        $schoolYearId = $request->get('school_year_id');
        
        $enrollments = StudentEnrollmentHistory::with(['student.studentProfile', 'section.strand', 'section.schoolYear', 'academicPeriod'])
            ->when($schoolYearId, function ($query, $schoolYearId) {
                $query->whereHas('section', function($q) use ($schoolYearId) {
                    $q->where('school_year_id', $schoolYearId);
                });
            })
            ->latest('created_at')
            ->paginate(50);

        return view('admin.registrar-admin.enrollment.history', compact('enrollments'));
    }

}
