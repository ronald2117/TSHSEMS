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
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate tables in correct order (children first, parents last)
        Attendance::truncate();
        StudentScore::truncate();
        QuarterlyGrade::truncate();
        Assessment::truncate();
        StudentSubjectEnrollment::truncate();
        ClassSchedule::truncate();
        StrandSubject::truncate();
        GradeTransmutation::truncate();
        GradingComponent::truncate();
        Subject::truncate();
        Section::truncate();
        AcademicPeriod::truncate();
        Strand::truncate();
        Track::truncate();
        SchoolYear::truncate();
        StudentProfile::truncate();
        TeacherProfile::truncate();
        User::truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

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
        $departments = ['Science Department', 'Mathematics Department', 'English Department', 'Social Studies', 'Filipino Department'];
        $specializations = ['Biology', 'Algebra & Calculus', 'English Literature', 'Philippine History', 'Filipino Language'];
        
        for ($i = 1; $i <= 5; $i++) {
            $teacher = User::create([
                'first_name' => "Teacher",
                'middle_name' => "Middle",
                'last_name' => "Surname $i",
                'email' => "teacher{$i}@tshsems.local",
                'login_id' => "T-2025-" . str_pad($i, 3, '0', STR_PAD_LEFT),
                'password' => Hash::make('password123'),
                'role' => 'teacher',
                'is_active' => true,
            ]);
            
            TeacherProfile::create([
                'user_id' => $teacher->id,
                'department' => $departments[$i - 1],
                'specialization' => $specializations[$i - 1],
            ]);
            
            $teachers[] = $teacher;
        }

        // Create Tracks (needed before strands)
        $academicTrack = Track::create([
            'code' => 'ACAD',
            'description' => 'Academic Track',
        ]);

        // Create Strands (needed before students)
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

        $humss = Strand::create([
            'track_id' => $academicTrack->id,
            'code' => 'HUMSS',
            'name' => 'HUMSS (Humanities and Social Sciences)',
        ]);

        // Create School Years
        $schoolYear = SchoolYear::create([
            'name' => '2025-2026',
            'start_date' => now()->setMonth(6)->setDay(1),
            'end_date' => now()->addYear()->setMonth(3)->setDay(31),
            'is_active' => true,
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

        $section11Ruby = Section::create([
            'school_year_id' => $schoolYear->id,
            'name' => 'Ruby',
            'grade_level' => 11,
            'strand_id' => $abm->id,
            'adviser_id' => $teachers[1]->id,
            'max_students' => 40,
        ]);

        $section12Diamond = Section::create([
            'school_year_id' => $schoolYear->id,
            'name' => 'Diamond',
            'grade_level' => 12,
            'strand_id' => $stem->id,
            'adviser_id' => $teachers[2]->id,
            'max_students' => 40,
        ]);

        $section12Ruby = Section::create([
            'school_year_id' => $schoolYear->id,
            'name' => 'Ruby',
            'grade_level' => 12,
            'strand_id' => $humss->id,
            'adviser_id' => $teachers[3]->id,
            'max_students' => 40,
        ]);

        // Create Students with complete profiles
        $students = [];
        $firstNames = ['Juan', 'Maria', 'Jose', 'Ana', 'Pedro', 'Rosa', 'Antonio', 'Carmen', 'Miguel', 'Elena', 
                       'Carlos', 'Sofia', 'Luis', 'Isabel', 'Fernando', 'Lucia', 'Rafael', 'Paula', 'Gabriel', 'Andrea',
                       'Diego', 'Valentina', 'Javier', 'Camila', 'Daniel'];
        $lastNames = ['Dela Cruz', 'Santos', 'Reyes', 'Garcia', 'Ramos', 'Mendoza', 'Torres', 'Flores', 'Gonzales', 'Rivera',
                      'Martinez', 'Lopez', 'Hernandez', 'Perez', 'Rodriguez', 'Sanchez', 'Ramirez', 'Cruz', 'Morales', 'Ortiz',
                      'Gutierrez', 'Jimenez', 'Alvarez', 'Romero', 'Medina'];
        $guardianNames = ['Juan Dela Cruz Sr.', 'Maria Santos', 'Jose Reyes', 'Ana Garcia', 'Pedro Ramos'];
        $addresses = [
            'Barangay 1, Taysan, Batangas',
            'Barangay 2, Taysan, Batangas',
            'Barangay 3, Taysan, Batangas',
            'Barangay Mabayabas, Taysan, Batangas',
            'Barangay San Isidro, Taysan, Batangas'
        ];
        
        $sections = [$section11Diamond, $section11Ruby, $section12Diamond, $section12Ruby];
        
        for ($i = 1; $i <= 25; $i++) {
            $section = $sections[($i - 1) % 4];
            $lrn = '10982390' . str_pad($i, 4, '0', STR_PAD_LEFT);
            $schoolId = '25' . str_pad($i, 4, '0', STR_PAD_LEFT); // 25 for year 2025, sequential 4-digit number
            
            $student = User::create([
                'first_name' => $firstNames[$i - 1],
                'middle_name' => 'M.',
                'last_name' => $lastNames[$i - 1],
                'email' => "student{$i}@tshsems.local",
                'login_id' => $lrn,
                'password' => Hash::make('password123'),
                'role' => 'student',
                'is_active' => true,
            ]);
            
            StudentProfile::create([
                'user_id' => $student->id,
                'lrn' => $lrn,
                'school_id' => $schoolId,
                'strand_id' => $section->strand_id,
                'current_section_id' => $section->id,
                'gender' => $i % 2 === 0 ? 'Male' : 'Female',
                'guardian_name' => $guardianNames[$i % 5],
                'guardian_contact' => '0912345' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'birthdate' => now()->subYears(rand(16, 18))->format('Y-m-d'),
                'address' => $addresses[$i % 5],
            ]);
            
            $students[] = $student;
        }

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

        // Enroll students in their section's schedules
        foreach ($students as $student) {
            $profile = $student->studentProfile;
            $section = $profile->currentSection;
            
            // Get all class schedules for this section
            $schedules = ClassSchedule::where('section_id', $section->id)->get();
            
            foreach ($schedules as $schedule) {
                StudentSubjectEnrollment::create([
                    'student_id' => $student->id,
                    'class_schedule_id' => $schedule->id,
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

        // Add scores for enrolled students in schedule1
        $enrolledStudents = StudentSubjectEnrollment::where('class_schedule_id', $schedule1->id)->get();
        
        foreach ($enrolledStudents as $enrollment) {
            $student = $enrollment->student;
            
            StudentScore::create([
                'assessment_id' => $assessment1->id,
                'student_id' => $student->id,
                'score' => rand(35, 50),
            ]);

            StudentScore::create([
                'assessment_id' => $assessment2->id,
                'student_id' => $student->id,
                'score' => rand(70, 95),
            ]);

            StudentScore::create([
                'assessment_id' => $assessment3->id,
                'student_id' => $student->id,
                'score' => rand(60, 95),
            ]);

            // Create quarterly grades
            $writtenScore = rand(35, 50);
            $performanceScore = rand(70, 95);
            $examScore = rand(60, 95);
            
            $initialGrade = ($writtenScore / 50 * 100 * 0.25) + 
                          ($performanceScore / 100 * 100 * 0.50) + 
                          ($examScore / 100 * 100 * 0.25);
                          
            $finalGrade = 75 + ($initialGrade - 60) * (100 - 75) / (100 - 60); // Simple transmutation

            QuarterlyGrade::create([
                'student_id' => $student->id,
                'class_schedule_id' => $schedule1->id,
                'quarter' => 1,
                'initial_grade' => $initialGrade,
                'final_grade' => round($finalGrade),
                'remarks' => $finalGrade >= 75 ? 'Passed' : 'Failed',
                'status' => rand(0, 1) ? 'Approved' : 'Submitted',
                'approved_at' => rand(0, 1) ? now() : null,
                'approved_by' => rand(0, 1) ? $registrar->id : null,
            ]);

            // Add sample attendance
            for ($day = 1; $day <= 20; $day++) {
                Attendance::create([
                    'student_id' => $student->id,
                    'class_schedule_id' => $schedule1->id,
                    'date' => now()->subDays(20 - $day),
                    'status' => ['Present', 'Present', 'Present', 'Late', 'Absent'][rand(0, 4)],
                    'recorded_by' => $teachers[0]->id,
                ]);
            }
        }

        // ========================================
        // COMPLETE SECTION WITH FULL ASSESSMENTS
        // ========================================
        
        // Create a complete test section for Grade 11 STEM
        $completeSection = Section::create([
            'school_year_id' => $schoolYear->id,
            'name' => 'Sapphire',
            'grade_level' => 11,
            'strand_id' => $stem->id,
            'adviser_id' => $teachers[4]->id,
            'max_students' => 40,
        ]);

        // Create 10 students for this section
        $completeStudents = [];
        $studentNames = [
            ['first' => 'Mark', 'last' => 'Johnson'],
            ['first' => 'Sarah', 'last' => 'Williams'],
            ['first' => 'David', 'last' => 'Brown'],
            ['first' => 'Emma', 'last' => 'Davis'],
            ['first' => 'James', 'last' => 'Miller'],
            ['first' => 'Olivia', 'last' => 'Wilson'],
            ['first' => 'Michael', 'last' => 'Moore'],
            ['first' => 'Sophia', 'last' => 'Taylor'],
            ['first' => 'Robert', 'last' => 'Anderson'],
            ['first' => 'Isabella', 'last' => 'Thomas'],
        ];

        for ($i = 0; $i < 10; $i++) {
            $lrn = '10982399' . str_pad($i + 1, 4, '0', STR_PAD_LEFT);
            $schoolId = '25' . str_pad($i + 26, 4, '0', STR_PAD_LEFT); // Continue from 250026 to avoid duplicates
            
            $student = User::create([
                'first_name' => $studentNames[$i]['first'],
                'middle_name' => 'T.',
                'last_name' => $studentNames[$i]['last'],
                'email' => "complete.student" . ($i + 1) . "@tshsems.local",
                'login_id' => $lrn,
                'password' => Hash::make('password123'),
                'role' => 'student',
                'is_active' => true,
            ]);
            
            StudentProfile::create([
                'user_id' => $student->id,
                'lrn' => $lrn,
                'school_id' => $schoolId,
                'strand_id' => $stem->id,
                'current_section_id' => $completeSection->id,
                'gender' => $i % 2 === 0 ? 'Male' : 'Female',
                'guardian_name' => $guardianNames[$i % 5],
                'guardian_contact' => '0923456' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'birthdate' => now()->subYears(16)->subMonths($i)->format('Y-m-d'),
                'address' => $addresses[$i % 5],
            ]);
            
            $completeStudents[] = $student;
        }

        // Create 3 subjects for this section
        $generalMath = Subject::create([
            'code' => 'GENMATH11',
            'name' => 'General Mathematics',
            'type' => 'Core',
            'units' => 3,
        ]);

        $earthScience = Subject::create([
            'code' => 'EARTHSCI',
            'name' => 'Earth Science',
            'type' => 'Core',
            'units' => 3,
        ]);

        $englishComp = Subject::create([
            'code' => 'ENG11',
            'name' => 'English for Academic Purposes',
            'type' => 'Core',
            'units' => 3,
        ]);

        // Link subjects to strand
        foreach ([$generalMath, $earthScience, $englishComp] as $subject) {
            StrandSubject::create([
                'strand_id' => $stem->id,
                'subject_id' => $subject->id,
                'grade_level' => 11,
                'is_required' => true,
            ]);
        }

        // Create class schedules for each subject
        $completeSchedules = [];
        
        $completeSchedules[] = ClassSchedule::create([
            'section_id' => $completeSection->id,
            'subject_id' => $generalMath->id,
            'teacher_id' => $teachers[0]->id,
            'academic_period_id' => $period1->id,
            'schedule_time' => 'MWF 7:00-8:00',
            'room' => '201',
        ]);

        $completeSchedules[] = ClassSchedule::create([
            'section_id' => $completeSection->id,
            'subject_id' => $earthScience->id,
            'teacher_id' => $teachers[1]->id,
            'academic_period_id' => $period1->id,
            'schedule_time' => 'TTh 7:00-8:30',
            'room' => '202',
        ]);

        $completeSchedules[] = ClassSchedule::create([
            'section_id' => $completeSection->id,
            'subject_id' => $englishComp->id,
            'teacher_id' => $teachers[2]->id,
            'academic_period_id' => $period1->id,
            'schedule_time' => 'MWF 9:00-10:00',
            'room' => '203',
        ]);

        // Enroll all students in all schedules
        foreach ($completeStudents as $student) {
            foreach ($completeSchedules as $schedule) {
                StudentSubjectEnrollment::create([
                    'student_id' => $student->id,
                    'class_schedule_id' => $schedule->id,
                    'status' => 'enrolled',
                ]);
            }
        }

        // Create assessments and scores for ALL quarters (1-4) for each schedule
        foreach ($completeSchedules as $schedule) {
            for ($quarter = 1; $quarter <= 4; $quarter++) {
                // Create 2 Quizzes (Written Work)
                $quiz1 = Assessment::create([
                    'class_schedule_id' => $schedule->id,
                    'title' => "Quiz 1 - Q{$quarter}",
                    'type' => 'written_work',
                    'max_score' => 50,
                    'quarter' => $quarter,
                    'is_published' => true,
                ]);

                $quiz2 = Assessment::create([
                    'class_schedule_id' => $schedule->id,
                    'title' => "Quiz 2 - Q{$quarter}",
                    'type' => 'written_work',
                    'max_score' => 50,
                    'quarter' => $quarter,
                    'is_published' => true,
                ]);

                // Create 2 Performance Tasks
                $pt1 = Assessment::create([
                    'class_schedule_id' => $schedule->id,
                    'title' => "Performance Task 1 - Q{$quarter}",
                    'type' => 'performance_task',
                    'max_score' => 100,
                    'quarter' => $quarter,
                    'is_published' => true,
                ]);

                $pt2 = Assessment::create([
                    'class_schedule_id' => $schedule->id,
                    'title' => "Performance Task 2 - Q{$quarter}",
                    'type' => 'performance_task',
                    'max_score' => 100,
                    'quarter' => $quarter,
                    'is_published' => true,
                ]);

                // Create 1 Quarterly Assessment
                $qa = Assessment::create([
                    'class_schedule_id' => $schedule->id,
                    'title' => "Quarterly Assessment - Q{$quarter}",
                    'type' => 'quarterly_assessment',
                    'max_score' => 100,
                    'quarter' => $quarter,
                    'is_published' => true,
                ]);

                // Create scores for all students in all assessments
                foreach ($completeStudents as $student) {
                    // Quiz 1 scores (70-95% of max)
                    StudentScore::create([
                        'assessment_id' => $quiz1->id,
                        'student_id' => $student->id,
                        'score' => rand(35, 48),
                    ]);

                    // Quiz 2 scores (70-95% of max)
                    StudentScore::create([
                        'assessment_id' => $quiz2->id,
                        'student_id' => $student->id,
                        'score' => rand(35, 48),
                    ]);

                    // Performance Task 1 scores (75-98% of max)
                    StudentScore::create([
                        'assessment_id' => $pt1->id,
                        'student_id' => $student->id,
                        'score' => rand(75, 98),
                    ]);

                    // Performance Task 2 scores (75-98% of max)
                    StudentScore::create([
                        'assessment_id' => $pt2->id,
                        'student_id' => $student->id,
                        'score' => rand(75, 98),
                    ]);

                    // Quarterly Assessment scores (70-95% of max)
                    StudentScore::create([
                        'assessment_id' => $qa->id,
                        'student_id' => $student->id,
                        'score' => rand(70, 95),
                    ]);
                }

                // Calculate and create quarterly grades for all students
                foreach ($completeStudents as $student) {
                    // Get all scores for this student in this quarter
                    $quiz1Score = StudentScore::where('assessment_id', $quiz1->id)
                        ->where('student_id', $student->id)->first()->score;
                    $quiz2Score = StudentScore::where('assessment_id', $quiz2->id)
                        ->where('student_id', $student->id)->first()->score;
                    $pt1Score = StudentScore::where('assessment_id', $pt1->id)
                        ->where('student_id', $student->id)->first()->score;
                    $pt2Score = StudentScore::where('assessment_id', $pt2->id)
                        ->where('student_id', $student->id)->first()->score;
                    $qaScore = StudentScore::where('assessment_id', $qa->id)
                        ->where('student_id', $student->id)->first()->score;

                    // Calculate percentages
                    $writtenAvg = (($quiz1Score / 50) + ($quiz2Score / 50)) / 2 * 100;
                    $performanceAvg = (($pt1Score / 100) + ($pt2Score / 100)) / 2 * 100;
                    $examPercentage = ($qaScore / 100) * 100;

                    // Calculate initial grade (weighted average)
                    $initialGrade = ($writtenAvg * 0.25) + 
                                  ($performanceAvg * 0.50) + 
                                  ($examPercentage * 0.25);

                    // Transmute to final grade (60-100 scale)
                    $finalGrade = 75 + ($initialGrade - 60) * (100 - 75) / (100 - 60);
                    $finalGrade = max(60, min(100, round($finalGrade)));

                    QuarterlyGrade::create([
                        'student_id' => $student->id,
                        'class_schedule_id' => $schedule->id,
                        'quarter' => $quarter,
                        'initial_grade' => round($initialGrade, 2),
                        'final_grade' => $finalGrade,
                        'remarks' => $finalGrade >= 75 ? 'Passed' : 'Failed',
                        'status' => 'Approved',
                        'approved_at' => now(),
                        'approved_by' => $registrar->id,
                    ]);
                }

                // Add attendance records for this quarter (20 days per quarter)
                foreach ($completeStudents as $student) {
                    $startDay = ($quarter - 1) * 25;
                    for ($day = 1; $day <= 20; $day++) {
                        $statuses = ['Present', 'Present', 'Present', 'Present', 'Present', 
                                   'Present', 'Present', 'Present', 'Late', 'Absent'];
                        Attendance::create([
                            'student_id' => $student->id,
                            'class_schedule_id' => $schedule->id,
                            'date' => now()->subDays($startDay + (20 - $day)),
                            'status' => $statuses[rand(0, 9)],
                            'recorded_by' => $schedule->teacher_id,
                        ]);
                    }
                }
            }
        }
    }
}
