<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\Strand;
use App\Models\Section;
use App\Models\ActivityLog;
use App\Models\ClassSchedule;
use App\Models\StudentEnrollmentHistory;
use App\Models\StudentSubjectEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'student')
            ->with('studentProfile.strand', 'studentProfile.currentSection');

        // Smart search across multiple fields
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('middle_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('login_id', 'like', "%{$search}%")
                  ->orWhereHas('studentProfile', function($q) use ($search) {
                      $q->where('lrn', 'like', "%{$search}%")
                        ->orWhere('guardian_name', 'like', "%{$search}%")
                        ->orWhere('guardian_contact', 'like', "%{$search}%")
                        ->orWhereHas('strand', function($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                              ->orWhere('code', 'like', "%{$search}%");
                        })
                        ->orWhereHas('currentSection', function($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                              ->orWhere('grade_level', 'like', "%{$search}%");
                        });
                  });
            });
        }

        $students = $query->latest()->paginate(20)->withQueryString();

        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        // Only registrar and super admin can create students
        if (!auth()->user()->isSuperAdmin() && auth()->user()->role !== 'registrar_admin') {
            abort(403, 'Unauthorized action.');
        }

        $strands = Strand::all();
        $sections = Section::with('schoolYear', 'strand')
            ->whereHas('schoolYear', function ($query) {
                $query->where('is_active', true);
            })
            ->get();

        return view('admin.students.create', compact('strands', 'sections'));
    }

    public function show(User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }

        $student->load('studentProfile.strand', 'studentProfile.currentSection.schoolYear');

        return view('admin.students.show', compact('student'));
    }

    public function store(Request $request)
    {
        // Only registrar and super admin can create students
        if (!auth()->user()->isSuperAdmin() && auth()->user()->role !== 'registrar_admin') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'lrn' => 'required|string|unique:student_profiles,lrn',
            'strand_id' => 'required|exists:strands,id',
            'current_section_id' => 'nullable|exists:sections,id',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'address' => 'nullable|string',
        ]);

        // Create user account
        $user = User::create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'suffix' => $validated['suffix'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'student',
            'login_id' => $validated['lrn'],
        ]);

        // Create student profile
        StudentProfile::create([
            'user_id' => $user->id,
            'lrn' => $validated['lrn'],
            'strand_id' => $validated['strand_id'],
            'current_section_id' => $validated['current_section_id'],
            'guardian_name' => $validated['guardian_name'],
            'guardian_contact' => $validated['guardian_contact'],
            'birthdate' => $validated['birthdate'],
            'address' => $validated['address'],
        ]);

        ActivityLog::log(
            'create',
            "Created student: {$user->first_name} {$user->last_name} (LRN: {$validated['lrn']})"
        );

        return redirect()->route('admin.students.index')
            ->with('success', 'Student created successfully.');
    }

    public function edit(User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }

        // Only registrar and super admin can edit students
        if (!auth()->user()->isSuperAdmin() && auth()->user()->role !== 'registrar_admin') {
            abort(403, 'Unauthorized action.');
        }

        $student->load('studentProfile');
        $strands = Strand::all();
        $sections = Section::with('schoolYear', 'strand')
            ->whereHas('schoolYear', function ($query) {
                $query->where('is_active', true);
            })
            ->get();

        return view('admin.students.edit', compact('student', 'strands', 'sections'));
    }

    public function update(Request $request, User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }

        // Only registrar and super admin can update students
        if (!auth()->user()->isSuperAdmin() && auth()->user()->role !== 'registrar_admin') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:50',
            'email' => ['required', 'email', Rule::unique('users')->ignore($student->id)],
            'password' => 'nullable|min:8|confirmed',
            'lrn' => ['required', 'string', Rule::unique('student_profiles')->ignore($student->studentProfile->id)],
            'strand_id' => 'required|exists:strands,id',
            'current_section_id' => 'nullable|exists:sections,id',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'address' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_avatar' => 'nullable|boolean',
        ]);

        // Handle avatar removal
        if ($request->has('remove_avatar') && $request->remove_avatar) {
            if ($student->avatar_path && \Storage::disk('public')->exists($student->avatar_path)) {
                \Storage::disk('public')->delete($student->avatar_path);
            }
            $student->avatar_path = null;
            $student->save();
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($student->avatar_path && \Storage::disk('public')->exists($student->avatar_path)) {
                \Storage::disk('public')->delete($student->avatar_path);
            }

            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $student->avatar_path = $avatarPath;
            $student->save();
        }

        // Update user account
        $userData = [
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'suffix' => $validated['suffix'],
            'email' => $validated['email'],
            'login_id' => $validated['lrn'],
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $student->update($userData);

        // Update student profile
        $student->studentProfile->update([
            'lrn' => $validated['lrn'],
            'strand_id' => $validated['strand_id'],
            'current_section_id' => $validated['current_section_id'],
            'guardian_name' => $validated['guardian_name'],
            'guardian_contact' => $validated['guardian_contact'],
            'birthdate' => $validated['birthdate'],
            'address' => $validated['address'],
        ]);

        ActivityLog::log(
            'update',
            "Updated student: {$student->first_name} {$student->last_name} (LRN: {$validated['lrn']})"
        );

        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }

        // Only registrar and super admin can delete students
        if (!auth()->user()->isSuperAdmin() && auth()->user()->role !== 'registrar_admin') {
            abort(403, 'Unauthorized action.');
        }

        $studentName = $student->full_name;
        $lrn = $student->studentProfile->lrn;
        
        $student->delete();

        ActivityLog::log(
            'delete',
            "Deleted student: {$studentName} (LRN: {$lrn})"
        );

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }

    public function toggleStatus(User $student)
    {
        // Check if the user is a student
        if ($student->role !== 'student') {
            abort(404);
        }

        // Check authorization: only super_admin and registrar_admin can toggle student status
        if (!auth()->user()->isSuperAdmin() && auth()->user()->role !== 'registrar_admin') {
            abort(403, 'Unauthorized action.');
        }

        // Toggle the is_active status
        $student->is_active = !$student->is_active;
        $student->save();

        $status = $student->is_active ? 'enabled' : 'disabled';
        $studentName = $student->full_name;
        $lrn = $student->studentProfile->lrn ?? 'N/A';

        ActivityLog::log(
            'update',
            "Student account {$status}: {$studentName} (LRN: {$lrn})"
        );

        return redirect()->back()
            ->with('success', "Student account has been {$status} successfully.");
    }

    /**
     * Show the bulk import form for creating students + enrollment.
     */
    public function bulkImportForm(Request $request)
    {
        // Only registrar and super admin can bulk import
        if (!auth()->user()->isSuperAdmin() && auth()->user()->role !== 'registrar_admin') {
            abort(403, 'Unauthorized action.');
        }

        // Download CSV template if requested
        if ($request->has('download') && $request->download === 'template') {
            $filename = 'student_bulk_import_template.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $columns = ['LRN', 'Last Name', 'First Name', 'Middle Name', 'Email', 'Guardian Name', 'Guardian Contact', 'Birthdate', 'Address'];
            $sampleData = [
                ['123456789012', 'Dela Cruz', 'Juan', 'Santos', 'juan.delacruz@email.com', 'Maria Dela Cruz', '09171234567', '2006-05-15', 'Taysan, Batangas'],
                ['987654321098', 'Santos', 'Maria', 'Garcia', 'maria.santos@email.com', 'Pedro Santos', '09181234567', '2007-02-20', 'Taysan, Batangas'],
            ];

            $callback = function () use ($columns, $sampleData) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($sampleData as $row) {
                    fputcsv($file, $row);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        $strands = Strand::all();
        $sections = Section::with(['strand', 'schoolYear'])
            ->whereHas('schoolYear', function ($query) {
                $query->where('is_active', true);
            })
            ->get();

        return view('admin.students.bulk-import', compact('strands', 'sections'));
    }

    /**
     * Process bulk import — creates student accounts + profiles + enrollment.
     */
    public function processBulkImport(Request $request)
    {
        // Only registrar and super admin can bulk import
        if (!auth()->user()->isSuperAdmin() && auth()->user()->role !== 'registrar_admin') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
            'strand_id' => 'required|exists:strands,id',
            'section_id' => 'required|exists:sections,id',
        ]);

        $section = Section::with(['schoolYear.academicPeriods'])->findOrFail($request->section_id);
        $strand = Strand::findOrFail($request->strand_id);
        $file = $request->file('file');

        // Get active academic period for this school year
        $academicPeriod = $section->schoolYear->academicPeriods()
            ->where('status', 'Active')
            ->first();

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            $fileContent = file_get_contents($file->getRealPath());
            $rows = array_map('str_getcsv', explode("\n", $fileContent));
            array_shift($rows); // Remove header row

            foreach ($rows as $index => $row) {
                if (empty(array_filter($row))) continue; // Skip empty rows

                $rowNumber = $index + 2;

                try {
                    $lrn = trim($row[0] ?? '');
                    $lastName = trim($row[1] ?? '');
                    $firstName = trim($row[2] ?? '');
                    $middleName = trim($row[3] ?? '');
                    $email = trim($row[4] ?? '');
                    $guardianName = trim($row[5] ?? '');
                    $guardianContact = trim($row[6] ?? '');
                    $birthdate = trim($row[7] ?? '');
                    $address = trim($row[8] ?? '');

                    // Validate required fields
                    if (empty($lrn) || empty($lastName) || empty($firstName)) {
                        $errors[] = "Row {$rowNumber}: Missing required fields (LRN, Last Name, or First Name)";
                        $errorCount++;
                        continue;
                    }

                    // Check if LRN already exists
                    if (StudentProfile::where('lrn', $lrn)->exists()) {
                        $errors[] = "Row {$rowNumber}: Student with LRN {$lrn} already exists";
                        $errorCount++;
                        continue;
                    }

                    // Check if email already exists (if provided)
                    if (!empty($email) && User::where('email', $email)->exists()) {
                        $errors[] = "Row {$rowNumber}: Email {$email} is already taken";
                        $errorCount++;
                        continue;
                    }

                    // Generate email if not provided
                    if (empty($email)) {
                        $email = strtolower($lrn) . '@student.tshsems.edu';
                    }

                    // Create user account (default password = LRN)
                    $user = User::create([
                        'first_name' => $firstName,
                        'middle_name' => $middleName ?: null,
                        'last_name' => $lastName,
                        'email' => $email,
                        'password' => Hash::make($lrn),
                        'role' => 'student',
                        'login_id' => $lrn,
                        'is_active' => true,
                    ]);

                    // Create student profile
                    StudentProfile::create([
                        'user_id' => $user->id,
                        'lrn' => $lrn,
                        'strand_id' => $strand->id,
                        'current_section_id' => $section->id,
                        'grade_level' => $section->grade_level,
                        'guardian_name' => $guardianName ?: null,
                        'guardian_contact' => $guardianContact ?: null,
                        'birthdate' => !empty($birthdate) ? $birthdate : null,
                        'address' => $address ?: null,
                    ]);

                    // Create enrollment history record if academic period exists
                    if ($academicPeriod) {
                        StudentEnrollmentHistory::create([
                            'student_id' => $user->id,
                            'section_id' => $section->id,
                            'academic_period_id' => $academicPeriod->id,
                            'grade_level' => $section->grade_level,
                            'status' => 'Enrolled',
                        ]);

                        // Enroll in all class schedules for this section
                        $classSchedules = ClassSchedule::where('section_id', $section->id)->get();
                        foreach ($classSchedules as $schedule) {
                            StudentSubjectEnrollment::firstOrCreate([
                                'student_id' => $user->id,
                                'class_schedule_id' => $schedule->id,
                            ]);
                        }
                    }

                    $successCount++;

                } catch (\Exception $e) {
                    $errors[] = "Row {$rowNumber}: " . $e->getMessage();
                    $errorCount++;
                }
            }

            DB::commit();

            ActivityLog::log(
                'bulk_import',
                "Bulk imported {$successCount} students to {$section->name} / {$strand->name} ({$errorCount} errors)"
            );

            $message = "Bulk import completed: {$successCount} students created & enrolled successfully";
            if ($errorCount > 0) {
                $message .= ", {$errorCount} errors occurred";
            }

            return redirect()
                ->route('admin.students.index')
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
