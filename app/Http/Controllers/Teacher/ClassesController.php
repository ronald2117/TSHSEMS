<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\StudentSubjectEnrollment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ClassesController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display teacher's assigned classes
     */
    public function index()
    {
        $classes = ClassSchedule::with([
                'subject',
                'section.strand',
                'academicPeriod.schoolYear',
                'enrollments' => function($query) {
                    $query->where('status', 'enrolled');
                }
            ])
            ->where('teacher_id', auth()->id())
            ->whereHas('academicPeriod', function ($query) {
                $query->where('status', 'Active');
            })
            ->orderBy('section_id')
            ->orderBy('subject_id')
            ->get();

        return view('teacher.classes.index', compact('classes'));
    }

    /**
     * Display class details and enrolled students
     */
    public function show(ClassSchedule $classSchedule)
    {
        // Ensure teacher owns this class
        $this->authorize('view', $classSchedule);

        $classSchedule->load([
            'subject',
            'section.strand',
            'academicPeriod.schoolYear',
            'teacher.teacherProfile'
        ]);

        $students = StudentSubjectEnrollment::with(['student.studentProfile.currentSection'])
            ->where('class_schedule_id', $classSchedule->id)
            ->where('status', 'enrolled')
            ->whereHas('student', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->get();

        return view('teacher.classes.show', compact('classSchedule', 'students'));
    }

    /**
     * Display class roster for printing
     */
    public function roster(ClassSchedule $classSchedule)
    {
        // Ensure teacher owns this class
        $this->authorize('view', $classSchedule);

        $classSchedule->load([
            'subject',
            'section.strand',
            'academicPeriod.schoolYear'
        ]);

        $students = StudentSubjectEnrollment::with(['student.studentProfile'])
            ->where('class_schedule_id', $classSchedule->id)
            ->where('status', 'enrolled')
            ->whereHas('student', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->get()
            ->sortBy('student.last_name');

        return view('teacher.classes.roster', compact('classSchedule', 'students'));
    }
}
