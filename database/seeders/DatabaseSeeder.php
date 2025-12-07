<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Models\SchoolYear;
use App\Models\AcademicPeriod;
use App\Models\Track;
use App\Models\Strand;
use App\Models\Section;
use App\Models\Subject;
use App\Models\StrandSubject;
use App\Models\ClassSchedule;
use App\Models\StudentSubjectEnrollment;
use App\Models\GradingComponent;
use App\Models\GradeTransmutation;
use App\Models\Assessment;
use App\Models\StudentScore;
use App\Models\QuarterlyGrade;
use App\Models\Attendance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin
        User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'admin@tshsems.local',
            'login_id' => 'ADMIN001',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        // Create Academic Admin
        User::create([
            'first_name' => 'Academic',
            'last_name' => 'Admin',
            'email' => 'academic@tshsems.local',
            'login_id' => 'ACAD001',
            'password' => Hash::make('password123'),
            'role' => 'academic_admin',
            'is_active' => true,
        ]);

        // Create Registrar Admin
        $registrar = User::create([
            'first_name' => 'Registrar',
            'last_name' => 'Admin',
            'email' => 'registrar@tshsems.local',
            'login_id' => 'REG001',
            'password' => Hash::make('password123'),
            'role' => 'registrar_admin',
            'is_active' => true,
        ]);

        // Create Teachers
        $teachers = [];
        for ($i = 1; $i <= 5; $i++) {
            $teacher = User::create([
                'first_name' => "Teacher",
                'last_name' => "Account $i",
                'email' => "teacher{$i}@tshsems.local",
                'login_id' => "T-2025-000{$i}",
                'password' => Hash::make('password123'),
                'role' => 'teacher',
                'is_active' => true,
            ]);
            $teacher->teacherProfile()->create();
            $teachers[] = $teacher;
        }

        // Create Students
        $students = [];
        for ($i = 1; $i <= 20; $i++) {
            $student = User::create([
                'first_name' => "Student",
                'last_name' => "Account $i",
                'email' => "student{$i}@tshsems.local",
                'login_id' => "LRN" . str_pad($i, 9, '0', STR_PAD_LEFT),
                'password' => Hash::make('password123'),
                'role' => 'student',
                'is_active' => true,
            ]);
            $students[] = $student;
        }

        // Create School Years
        $schoolYear = SchoolYear::create([
            'name' => '2025-2026',
            'start_date' => now()->setMonth(6)->setDay(1),
            'end_date' => now()->addYear()->setMonth(3)->setDay(31),
            'is_active' => true,
        ]);

        // Create Academic Periods
        $period1 = AcademicPeriod::create([
            'school_year_id' => $schoolYear->id,
            'name' => '1st Semester',
            'status' => 'Active',
        ]);

        $period2 = AcademicPeriod::create([
            'school_year_id' => $schoolYear->id,
            'name' => '2nd Semester',
            'status' => 'Active',
        ]);

        // Create Tracks
        $academicTrack = Track::create([
            'code' => 'ACAD',
            'description' => 'Academic Track',
        ]);

        // Create Strands
        $stem = Strand::create([
            'track_id' => $academicTrack->id,
            'code' => 'STEM',
            'name' => 'STEM (Science, Technology, Engineering, Mathematics)',
        ]);

        $abm = Strand::create([
            'track_id' => $academicTrack->id,
            'code' => 'ABM',
            'name' => 'ABM (Accountancy, Business, Management)',
        ]);

        // Create Sections
        $section11Diamond = Section::create([
            'school_year_id' => $schoolYear->id,
            'name' => 'Diamond',
            'grade_level' => 11,
            'strand_id' => $stem->id,
            'adviser_id' => $teachers[0]->id,
            'max_students' => 40,
        ]);

        $section12Diamond = Section::create([
            'school_year_id' => $schoolYear->id,
            'name' => 'Diamond',
            'grade_level' => 12,
            'strand_id' => $abm->id,
            'adviser_id' => $teachers[1]->id,
            'max_students' => 40,
        ]);

        // Assign students to sections
        foreach ($students as $index => $student) {
            $section = $index < 10 ? $section11Diamond : $section12Diamond;
            $student->studentProfile()->update([
                'current_section_id' => $section->id,
                'strand_id' => $section->strand_id,
            ]);
        }

        // Create Subjects
        $math = Subject::create([
            'code' => 'GENMATH',
            'name' => 'General Mathematics',
            'type' => 'Core',
            'units' => 3,
        ]);

        $science = Subject::create([
            'code' => 'PHYS',
            'name' => 'Physics',
            'type' => 'Core',
            'units' => 3,
        ]);

        $english = Subject::create([
            'code' => 'ENG',
            'name' => 'English',
            'type' => 'Core',
            'units' => 3,
        ]);

        $accounting = Subject::create([
            'code' => 'ACCT101',
            'name' => 'Accounting 101',
            'type' => 'Core',
            'units' => 3,
        ]);

        // Create Strand-Subject Links
        StrandSubject::create([
            'strand_id' => $stem->id,
            'subject_id' => $math->id,
            'grade_level' => 11,
            'is_required' => true,
        ]);

        StrandSubject::create([
            'strand_id' => $stem->id,
            'subject_id' => $science->id,
            'grade_level' => 11,
            'is_required' => true,
        ]);

        StrandSubject::create([
            'strand_id' => $abm->id,
            'subject_id' => $accounting->id,
            'grade_level' => 12,
            'is_required' => true,
        ]);

        // Create Class Schedules
        $schedule1 = ClassSchedule::create([
            'section_id' => $section11Diamond->id,
            'subject_id' => $math->id,
            'teacher_id' => $teachers[0]->id,
            'academic_period_id' => $period1->id,
            'schedule_time' => 'MWF 8:00-9:00',
            'room' => '101',
        ]);

        $schedule2 = ClassSchedule::create([
            'section_id' => $section11Diamond->id,
            'subject_id' => $science->id,
            'teacher_id' => $teachers[1]->id,
            'academic_period_id' => $period1->id,
            'schedule_time' => 'TTh 8:00-9:30',
            'room' => '102',
        ]);

        // Enroll students
        foreach ($students as $index => $student) {
            if ($index < 10) {
                StudentSubjectEnrollment::create([
                    'student_id' => $student->id,
                    'class_schedule_id' => $schedule1->id,
                    'status' => 'enrolled',
                ]);

                StudentSubjectEnrollment::create([
                    'student_id' => $student->id,
                    'class_schedule_id' => $schedule2->id,
                    'status' => 'enrolled',
                ]);
            }
        }

        // Create Grading Components
        GradingComponent::create([
            'subject_type' => 'Core',
            'written_weight' => 0.25,
            'performance_weight' => 0.50,
            'exam_weight' => 0.25,
        ]);

        // Create Grade Transmutations
        $transmutations = [
            ['min' => 90, 'max' => 100, 'grade' => 98],
            ['min' => 85, 'max' => 89.99, 'grade' => 92],
            ['min' => 80, 'max' => 84.99, 'grade' => 87],
            ['min' => 75, 'max' => 79.99, 'grade' => 82],
            ['min' => 70, 'max' => 74.99, 'grade' => 77],
            ['min' => 65, 'max' => 69.99, 'grade' => 72],
            ['min' => 60, 'max' => 64.99, 'grade' => 67],
            ['min' => 0, 'max' => 59.99, 'grade' => 60],
        ];

        foreach ($transmutations as $t) {
            GradeTransmutation::create([
                'min_score' => $t['min'],
                'max_score' => $t['max'],
                'transmuted_grade' => $t['grade'],
            ]);
        }

        // Create Assessments
        $assessment1 = Assessment::create([
            'class_schedule_id' => $schedule1->id,
            'title' => 'Quiz 1',
            'type' => 'written_work',
            'max_score' => 50,
            'quarter' => 1,
            'is_published' => true,
        ]);

        $assessment2 = Assessment::create([
            'class_schedule_id' => $schedule1->id,
            'title' => 'Performance Task 1',
            'type' => 'performance_task',
            'max_score' => 100,
            'quarter' => 1,
            'is_published' => true,
        ]);

        $assessment3 = Assessment::create([
            'class_schedule_id' => $schedule1->id,
            'title' => 'Quarterly Exam',
            'type' => 'quarterly_assessment',
            'max_score' => 100,
            'quarter' => 1,
            'is_published' => true,
        ]);

        // Add scores for first 10 students
        for ($i = 0; $i < 10; $i++) {
            StudentScore::create([
                'assessment_id' => $assessment1->id,
                'student_id' => $students[$i]->id,
                'score' => rand(35, 50),
            ]);

            StudentScore::create([
                'assessment_id' => $assessment2->id,
                'student_id' => $students[$i]->id,
                'score' => rand(70, 95),
            ]);

            StudentScore::create([
                'assessment_id' => $assessment3->id,
                'student_id' => $students[$i]->id,
                'score' => rand(60, 95),
            ]);

            // Create quarterly grades
            $initialGrade = (rand(35, 50) + rand(70, 95) + rand(60, 95)) / 3 * (0.25 + 0.50 + 0.25);
            $finalGrade = GradeTransmutation::transmute($initialGrade);

            QuarterlyGrade::create([
                'student_id' => $students[$i]->id,
                'class_schedule_id' => $schedule1->id,
                'quarter' => 1,
                'initial_grade' => $initialGrade,
                'final_grade' => $finalGrade,
                'remarks' => $finalGrade >= 75 ? 'Passed' : 'Failed',
                'status' => 'Approved',
                'approved_at' => now(),
                'approved_by' => $registrar->id,
            ]);

            // Add sample attendance
            for ($day = 1; $day <= 20; $day++) {
                Attendance::create([
                    'student_id' => $students[$i]->id,
                    'class_schedule_id' => $schedule1->id,
                    'date' => now()->subDays(20 - $day),
                    'status' => ['Present', 'Present', 'Present', 'Late', 'Absent'][rand(0, 4)],
                    'recorded_by' => $teachers[0]->id,
                ]);
            }
        }
    }
}
