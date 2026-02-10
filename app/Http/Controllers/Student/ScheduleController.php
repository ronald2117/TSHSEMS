<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display student's class schedule
     */
    public function index()
    {
        $student = auth()->user()->studentProfile;
        
        // Get all class schedules for student's section
        $schedules = ClassSchedule::with(['subject', 'teacher', 'academicPeriod'])
            ->where('section_id', $student->current_section_id)
            ->whereHas('academicPeriod', function ($query) {
                $query->where('status', 'Active');
            })
            ->get();

        // Group by day of week from schedule_details JSON
        // Since we don't have day_of_week column, we'll organize schedules differently
        // or just display them in a simple list format
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        return view('student.schedule.index', compact('schedules', 'daysOfWeek', 'student'));
    }
}
