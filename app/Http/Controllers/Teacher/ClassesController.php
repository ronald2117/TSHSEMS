<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\StudentSubjectEnrollment;
use Illuminate\Http\Request;

class ClassesController extends Controller
{
    /**
     * Display teacher's assigned classes
     */
    public function index()
    {
        $classes = ClassSchedule::with(['subject', 'section.strand', 'academicPeriod', 'schoolYear'])
            ->where('teacher_id', auth()->user()->teacherProfile->id)
            ->whereHas('academicPeriod', function ($query) {
                $query->where('is_current', true);
            })
            ->orderBy('day_of_week')
            ->orderBy('start_time')
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
            'academicPeriod',
            'schoolYear',
            'teacher.user'
        ]);

        $students = StudentSubjectEnrollment::with(['student.user', 'student.section'])
            ->where('class_schedule_id', $classSchedule->id)
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
            'academicPeriod',
            'schoolYear'
        ]);

        $students = StudentSubjectEnrollment::with(['student.user'])
            ->where('class_schedule_id', $classSchedule->id)
            ->whereHas('student', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->get()
            ->sortBy('student.user.name');

        return view('teacher.classes.roster', compact('classSchedule', 'students'));
    }
}
