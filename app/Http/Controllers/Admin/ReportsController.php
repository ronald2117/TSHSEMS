<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentProfile;
use App\Models\Section;
use App\Models\SchoolYear;
use App\Models\QuarterlyGrade;
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
        $sections = Section::with(['strand', 'schoolYear'])->get();
        
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
}
