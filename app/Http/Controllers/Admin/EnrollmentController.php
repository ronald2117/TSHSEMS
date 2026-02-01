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
        $students = StudentProfile::with(['user', 'currentSection.strand'])
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
            'enrollment_date' => 'required|date',
        ]);

        $student = StudentProfile::findOrFail($studentId);
        $section = Section::with('schoolYear')->findOrFail($validated['section_id']);

        DB::beginTransaction();
        try {
            // Update student's current section
            $student->update([
                'current_section_id' => $section->id,
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

            // Log activity
            ActivityLog::log(
                'student_enrolled',
                "Enrolled student {$student->user->full_name} to section {$section->name}"
            );

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
        $newSection = Section::with('schoolYear')->findOrFail($validated['new_section_id']);

        if ($student->current_section_id == $newSection->id) {
            return back()->withErrors(['error' => 'Student is already enrolled in this section.']);
        }

        DB::beginTransaction();
        try {
            // Update old enrollment history to 'transferred'
            StudentEnrollmentHistory::where('student_id', $student->id)
                ->where('section_id', $oldSection->id)
                ->where('status', 'enrolled')
                ->update(['status' => 'transferred']);

            // Update student's current section
            $student->update([
                'current_section_id' => $newSection->id,
            ]);

            // Create new enrollment history record
            StudentEnrollmentHistory::create([
                'student_id' => $student->id,
                'section_id' => $newSection->id,
                'school_year_id' => $newSection->school_year_id,
                'enrollment_date' => $validated['transfer_date'],
                'status' => 'enrolled',
            ]);

            // Remove old subject enrollments
            StudentSubjectEnrollment::where('student_id', $student->id)
                ->whereHas('classSchedule', function ($query) use ($oldSection) {
                    $query->where('section_id', $oldSection->id);
                })
                ->delete();

            // Enroll student in all subjects for the new section
            $classSchedules = ClassSchedule::where('section_id', $newSection->id)->get();
            
            foreach ($classSchedules as $schedule) {
                StudentSubjectEnrollment::firstOrCreate([
                    'student_id' => $student->id,
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

            // Update enrollment history to 'withdrawn'
            StudentEnrollmentHistory::where('student_id', $student->id)
                ->where('section_id', $student->current_section_id)
                ->where('status', 'enrolled')
                ->update(['status' => 'withdrawn']);

            // Remove subject enrollments
            StudentSubjectEnrollment::where('student_id', $student->id)
                ->whereHas('classSchedule', function ($query) use ($section) {
                    $query->where('section_id', $section->id);
                })
                ->delete();

            // Update student's current section to null
            $student->update([
                'current_section_id' => null,
            ]);

            DB::commit();

            // Log activity
            ActivityLog::log(
                'student_unenrollment',
                "Unenrolled student {$student->user->full_name} from {$section->name}"
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
        
        $enrollments = StudentEnrollmentHistory::with(['student.user', 'section.strand', 'schoolYear'])
            ->when($schoolYearId, function ($query, $schoolYearId) {
                $query->where('school_year_id', $schoolYearId);
            })
            ->latest('enrollment_date')
            ->paginate(50);

        return view('admin.registrar-admin.enrollment.history', compact('enrollments'));
    }

    /**
     * Show bulk import form
     */
    public function bulkImportForm(Request $request)
    {
        // Download CSV template if requested
        if ($request->has('download') && $request->download === 'template') {
            $filename = 'student_enrollment_template.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $columns = ['LRN', 'Last Name', 'First Name', 'Middle Name', 'Email'];
            $sampleData = [
                ['123456789012', 'Dela Cruz', 'Juan', 'Santos', 'juan.delacruz@example.com'],
                ['987654321098', 'Santos', 'Maria', 'Garcia', 'maria.santos@example.com'],
            ];

            $callback = function() use ($columns, $sampleData) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($sampleData as $row) {
                    fputcsv($file, $row);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        $sections = Section::with(['strand', 'schoolYear'])
            ->where('school_year_id', function ($query) {
                $query->select('id')
                    ->from('school_years')
                    ->where('is_active', true)
                    ->limit(1);
            })
            ->get();

        return view('admin.registrar-admin.enrollment.bulk-import', compact('sections'));
    }

    /**
     * Process bulk import from CSV/Excel file
     */
    public function processBulkImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:2048',
            'section_id' => 'required|exists:sections,id',
            'enrollment_date' => 'required|date',
        ]);

        $section = Section::with('schoolYear')->findOrFail($request->section_id);
        $file = $request->file('file');
        
        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            // Read file content
            $fileContent = file_get_contents($file->getRealPath());
            $rows = array_map('str_getcsv', explode("\n", $fileContent));
            $header = array_shift($rows); // Remove header row

            // Expected columns: lrn, last_name, first_name, middle_name, email
            foreach ($rows as $index => $row) {
                if (empty($row[0])) continue; // Skip empty rows

                $rowNumber = $index + 2; // +2 because of header and 0-based index

                try {
                    // Extract data from CSV
                    $lrn = trim($row[0] ?? '');
                    $lastName = trim($row[1] ?? '');
                    $firstName = trim($row[2] ?? '');
                    $middleName = trim($row[3] ?? '');
                    $email = trim($row[4] ?? '');

                    if (empty($lrn) || empty($lastName) || empty($firstName)) {
                        $errors[] = "Row {$rowNumber}: Missing required fields (LRN, Last Name, or First Name)";
                        $errorCount++;
                        continue;
                    }

                    // Find student by LRN
                    $student = StudentProfile::where('lrn', $lrn)->first();

                    if (!$student) {
                        $errors[] = "Row {$rowNumber}: Student with LRN {$lrn} not found";
                        $errorCount++;
                        continue;
                    }

                    // Check if already enrolled in this section
                    $alreadyEnrolled = StudentEnrollmentHistory::where('student_id', $student->id)
                        ->where('section_id', $section->id)
                        ->where('school_year_id', $section->school_year_id)
                        ->where('status', 'enrolled')
                        ->exists();

                    if ($alreadyEnrolled) {
                        $errors[] = "Row {$rowNumber}: Student {$lrn} already enrolled in this section";
                        $errorCount++;
                        continue;
                    }

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
                        'enrollment_date' => $request->enrollment_date,
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

                    $successCount++;

                } catch (\Exception $e) {
                    $errors[] = "Row {$rowNumber}: {$e->getMessage()}";
                    $errorCount++;
                }
            }

            DB::commit();

            // Log activity
            ActivityLog::log(
                'bulk_enrollment',
                "Bulk enrolled {$successCount} students to section {$section->name} ({$errorCount} errors)"
            );

            $message = "Bulk import completed: {$successCount} students enrolled successfully";
            if ($errorCount > 0) {
                $message .= ", {$errorCount} errors occurred";
            }

            return redirect()
                ->route('admin.enrollment.index')
                ->with('success', $message)
                ->with('import_errors', $errors);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Bulk import failed: ' . $e->getMessage()]);
        }
    }
}
