<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentProfile;
use App\Models\Section;
use App\Models\SchoolYear;
use App\Models\QuarterlyGrade;
use App\Models\ClassSchedule;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function studentList(Request $request)
    {
        $sections = Section::with(['strand', 'schoolYear'])->get();
        $schoolYears = SchoolYear::orderBy('start_date', 'desc')->get();

        $query = StudentProfile::with(['user', 'currentSection.strand', 'strand']);

        if ($request->filled('section_id')) {
            $query->where('current_section_id', $request->section_id);
        }

        if ($request->filled('strand_id')) {
            $query->where('strand_id', $request->strand_id);
        }

        $students = $query->paginate(50);

        return view('admin.reports.student-list', compact('students', 'sections', 'schoolYears'));
    }

    public function gradesSummary(Request $request)
    {
        $sections = Section::with(['strand', 'schoolYear', 'studentProfiles.user'])
            ->whereHas('schoolYear', function($q) {
                $q->where('is_active', true);
            })
            ->get();
        
        return view('admin.reports.grades-summary', compact('sections'));
    }

    public function form137($studentId)
    {
        $student = StudentProfile::with([
            'user',
            'strand',
            'currentSection'
        ])->findOrFail($studentId);

        $grades = QuarterlyGrade::with(['classSchedule.subject', 'classSchedule.academicPeriod'])
            ->where('student_id', $student->user_id)
            ->where('status', 'Approved')
            ->orderBy('quarter')
            ->get()
            ->groupBy(function($grade) {
                return $grade->classSchedule->academicPeriod->school_year_id;
            });

        return view('admin.reports.form137', compact('student', 'grades'));
    }

    public function form138($studentId, Request $request)
    {
        $student = StudentProfile::with([
            'user',
            'strand',
            'currentSection'
        ])->findOrFail($studentId);

        $schoolYearId = $request->query('school_year_id');
        $quarter = $request->query('quarter');

        $grades = QuarterlyGrade::with(['classSchedule.subject', 'classSchedule.academicPeriod'])
            ->where('student_id', $student->user_id)
            ->where('status', 'Approved');

        if ($schoolYearId) {
            $grades->whereHas('classSchedule.academicPeriod', function($q) use ($schoolYearId) {
                $q->where('school_year_id', $schoolYearId);
            });
        }

        if ($quarter) {
            $grades->where('quarter', $quarter);
        }

        $grades = $grades->orderBy('quarter')->get();

        return view('admin.reports.form138', compact('student', 'grades'));
    }

    public function masterList($sectionId)
    {
        $section = Section::with([
            'strand',
            'schoolYear',
            'adviser',
            'studentProfiles.user'
        ])->findOrFail($sectionId);

        return view('admin.reports.master-list', compact('section'));
    }

    public function attendanceSummary(Request $request)
    {
        $sections = Section::with(['strand', 'schoolYear'])->get();
        
        return view('admin.reports.attendance-summary', compact('sections'));
    }

    public function exportStudents(Request $request)
    {
        $query = StudentProfile::with(['user', 'currentSection.strand', 'strand']);

        if ($request->filled('section_id')) {
            $query->where('current_section_id', $request->section_id);
        }

        if ($request->filled('strand_id')) {
            $query->where('strand_id', $request->strand_id);
        }

        $students = $query->get();

        $filename = 'students_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 support
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // CSV Headers
            fputcsv($file, ['#', 'LRN', 'Last Name', 'First Name', 'Middle Name', 'Section', 'Strand', 'Grade Level']);
            
            // CSV Rows
            foreach ($students as $index => $student) {
                fputcsv($file, [
                    $index + 1,
                    $student->lrn,
                    $student->user->last_name ?? '',
                    $student->user->first_name ?? '',
                    $student->user->middle_name ?? '',
                    $student->currentSection->name ?? 'Not Enrolled',
                    $student->strand->name ?? $student->currentSection->strand->name ?? 'N/A',
                    'Grade ' . ($student->grade_level ?? 'N/A'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportGrades(Request $request)
    {
        if (!$request->filled('section_id')) {
            return redirect()->route('admin.reports.grades')->withErrors(['error' => 'Please select a section first.']);
        }

        $section = Section::with(['strand', 'schoolYear', 'studentProfiles.user'])->findOrFail($request->section_id);
        $classSchedules = ClassSchedule::where('section_id', $section->id)->with('subject')->get();
        $students = $section->studentProfiles()->with('user')->get();
        $quarterFilter = $request->quarter;

        $filename = 'grades_' . str_replace(' ', '_', $section->name) . '_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($students, $classSchedules, $quarterFilter) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 support
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Build headers
            $headerRow = ['#', 'Student Name'];
            foreach ($classSchedules as $schedule) {
                $headerRow[] = $schedule->subject->code ?? 'SUB';
            }
            $headerRow[] = 'GWA';
            $headerRow[] = 'Remarks';
            
            fputcsv($file, $headerRow);
            
            // Export student grades
            foreach ($students as $index => $student) {
                $row = [
                    $index + 1,
                    ($student->user->last_name ?? '') . ', ' . ($student->user->first_name ?? '')
                ];
                
                $totalGrade = 0;
                $gradeCount = 0;
                
                foreach ($classSchedules as $schedule) {
                    $gradeQuery = QuarterlyGrade::where('student_id', $student->user_id)
                        ->where('class_schedule_id', $schedule->id)
                        ->where('status', 'Approved');
                    
                    if ($quarterFilter) {
                        $grade = $gradeQuery->where('quarter', $quarterFilter)->first();
                    } else {
                        $grade = $gradeQuery->orderBy('quarter', 'desc')->first();
                    }
                    
                    if ($grade && $grade->final_grade) {
                        $row[] = number_format($grade->final_grade, 0);
                        $totalGrade += $grade->final_grade;
                        $gradeCount++;
                    } else {
                        $row[] = '-';
                    }
                }
                
                $gwa = $gradeCount > 0 ? $totalGrade / $gradeCount : null;
                $row[] = $gwa ? number_format($gwa, 2) : '-';
                $row[] = $gwa ? ($gwa >= 75 ? 'Passed' : 'Failed') : '-';
                
                fputcsv($file, $row);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportAttendance(Request $request)
    {
        if (!$request->filled('section_id')) {
            return redirect()->route('admin.reports.attendance')->withErrors(['error' => 'Please select a section first.']);
        }

        $section = Section::with(['strand', 'schoolYear', 'studentProfiles.user'])->findOrFail($request->section_id);
        $students = $section->studentProfiles()->with('user')->get();
        $month = $request->input('month', date('Y-m'));
        $monthStart = Carbon::parse($month)->startOfMonth();
        $monthEnd = Carbon::parse($month)->endOfMonth();
        
        // Get all attendance records
        $attendanceRecords = Attendance::whereHas('classSchedule', function($q) use ($section) {
            $q->where('section_id', $section->id);
        })
        ->whereBetween('date', [$monthStart, $monthEnd])
        ->get()
        ->groupBy('student_id');

        $filename = 'attendance_' . str_replace(' ', '_', $section->name) . '_' . $monthStart->format('Y-m') . '_' . date('His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($students, $attendanceRecords, $monthStart) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 support
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, ['#', 'Student Name', 'Present', 'Late', 'Absent', 'Excused', 'Total Days', '% Present']);
            
            // Export student attendance
            foreach ($students as $index => $student) {
                $studentAttendance = $attendanceRecords->get($student->user_id, collect());
                $present = $studentAttendance->where('status', 'Present')->count();
                $late = $studentAttendance->where('status', 'Late')->count();
                $absent = $studentAttendance->where('status', 'Absent')->count();
                $excused = $studentAttendance->where('status', 'Excused')->count();
                $totalRecorded = $present + $late + $absent + $excused;
                $percentPresent = $totalRecorded > 0 ? (($present + $late) / $totalRecorded) * 100 : 0;
                
                $row = [
                    $index + 1,
                    ($student->user->last_name ?? '') . ', ' . ($student->user->first_name ?? ''),
                    $present,
                    $late,
                    $absent,
                    $excused,
                    $totalRecorded,
                    number_format($percentPresent, 1) . '%'
                ];
                
                fputcsv($file, $row);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
