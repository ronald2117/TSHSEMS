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
        $schedules = ClassSchedule::with(['subject', 'teacher.user', 'academicPeriod'])
            ->where('section_id', $student->current_section_id)
            ->whereHas('academicPeriod', function ($query) {
                $query->where('status', 'Active');
            })
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        // Group by day of week
        $scheduleByDay = $schedules->groupBy('day_of_week');

        $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        return view('student.schedule.index', compact('scheduleByDay', 'daysOfWeek', 'student'));
    }
}
