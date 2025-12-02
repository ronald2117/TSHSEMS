<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ==========================================
        // 1. AUTHENTICATION & USERS
        // ==========================================

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('login_id')->unique()->nullable()
                ->comment('Student LRN or Employee ID for Hybrid Login');
            $table->string('email')->unique();
            $table->string('password');
            
            $table->enum('role', [
                'super_admin', 
                'academic_admin', 
                'registrar_admin', 
                'technical_admin', 
                'teacher', 
                'student'
            ])->default('student')->index();

            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->string('avatar_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        // ==========================================
        // 2. ACADEMIC SETUP (Structure)
        // ==========================================

        Schema::create('school_years', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "2025-2026"
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        Schema::create('academic_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_year_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // e.g., "1st Semester"
            $table->enum('status', ['Active', 'Closed'])->default('Active');
            $table->timestamps();
        });

        Schema::create('tracks', function (Blueprint $table) {
            $table->id();
            $table->string('code'); // e.g., "ACAD", "TVL"
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('strands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('track_id')->constrained()->cascadeOnDelete();
            $table->string('code'); // e.g., "STEM", "ABM"
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_year_id')->constrained();
            $table->string('name'); // e.g., "Diamond"
            $table->integer('grade_level'); // 11 or 12
            $table->foreignId('strand_id')->constrained();
            $table->foreignId('adviser_id')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        // ==========================================
        // 3. USER PROFILES (Details)
        // ==========================================

        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('lrn')->unique(); // Learner Reference Number
            $table->foreignId('current_section_id')->nullable()->constrained('sections');
            $table->foreignId('strand_id')->constrained();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_contact')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });

        Schema::create('teacher_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('department')->nullable(); // e.g., "Science Dept"
            $table->string('specialization')->nullable();
            $table->timestamps();
        });

        // ==========================================
        // 4. SUBJECTS & SCHEDULES (The "Load")
        // ==========================================

        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('code'); // e.g., "GENMATH"
            $table->string('name');
            $table->enum('type', ['Core', 'Applied', 'Specialized']);
            $table->timestamps();
        });

        Schema::create('class_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained();
            $table->foreignId('teacher_id')->constrained('users');
            $table->foreignId('academic_period_id')->constrained(); // Links to Semester
            $table->string('schedule_time')->nullable(); // e.g., "MWF 8:00-9:00"
            $table->string('room')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Enrollment Table: Links Student -> Class Schedule
        Schema::create('student_subject_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('class_schedule_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['enrolled', 'dropped'])->default('enrolled');
            $table->timestamps();
        });

        // Enrollment History: Tracks which section a student belonged to historically
        Schema::create('student_enrollment_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('section_id')->constrained();
            $table->foreignId('academic_period_id')->constrained();
            $table->integer('grade_level');
            $table->string('status'); // Enrolled, Transferred Out, etc.
            $table->timestamps();
        });

        // ==========================================
        // 5. GRADING ENGINE (DepEd Compliant)
        // ==========================================

        Schema::create('grading_components', function (Blueprint $table) {
            $table->id();
            $table->enum('subject_type', ['Core', 'Applied', 'Specialized']);
            $table->decimal('written_weight', 3, 2); // 0.25
            $table->decimal('performance_weight', 3, 2); // 0.50
            $table->decimal('exam_weight', 3, 2); // 0.25
            $table->timestamps();
        });

        Schema::create('grade_transmutations', function (Blueprint $table) {
            $table->id();
            $table->decimal('min_score', 5, 2); 
            $table->decimal('max_score', 5, 2);
            $table->integer('transmuted_grade'); // 60-100
            $table->timestamps();
        });

        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_schedule_id')->constrained()->cascadeOnDelete();
            $table->string('title'); // "Quiz #1", "PT #2"
            $table->enum('type', ['written_work', 'performance_task', 'quarterly_assessment']);
            $table->integer('max_score');
            $table->integer('quarter'); // 1, 2, 3, or 4
            $table->boolean('is_published')->default(false); // Teacher visibility toggle
            $table->timestamps();
        });

        Schema::create('student_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users');
            $table->decimal('score', 5, 2);
            $table->timestamps();
        });

        Schema::create('quarterly_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('class_schedule_id')->constrained();
            $table->integer('quarter');
            $table->decimal('initial_grade', 5, 2); // The computed raw score
            $table->integer('final_grade'); // The transmuted grade (75, 80, etc)
            $table->string('remarks')->nullable(); // Passed/Failed
            
            // Workflow Status for Registrar Approval
            $table->enum('status', ['Draft', 'Submitted', 'Approved', 'Returned'])->default('Draft');
            
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('grade_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quarterly_grade_id')->constrained('quarterly_grades')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->integer('old_grade');
            $table->integer('new_grade');
            $table->string('reason'); // Required field for changes
            $table->timestamps();
        });

        // ==========================================
        // 6. OPERATIONS & LOGS
        // ==========================================

        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('class_schedule_id')->constrained();
            $table->date('date');
            $table->enum('status', ['Present', 'Absent', 'Late', 'Excused']);
            $table->string('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('document_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users');
            $table->string('type'); // "Form 137", "Certificate of Enrollment"
            $table->string('status'); // "Pending", "Ready", "Claimed"
            $table->foreignId('processed_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->boolean('is_public')->default(false); // For Landing Page
            $table->foreignId('author_id')->constrained('users');
            $table->string('target_role')->nullable(); // "student", "teacher", or null for all
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->string('action'); // "LOGIN", "UPDATE_GRADE"
            $table->text('description');
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('document_requests');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('grade_audit_logs');
        Schema::dropIfExists('quarterly_grades');
        Schema::dropIfExists('student_scores');
        Schema::dropIfExists('assessments');
        Schema::dropIfExists('grade_transmutations');
        Schema::dropIfExists('grading_components');
        Schema::dropIfExists('student_enrollment_history');
        Schema::dropIfExists('student_subject_enrollments');
        Schema::dropIfExists('class_schedules');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('teacher_profiles');
        Schema::dropIfExists('student_profiles');
        Schema::dropIfExists('sections');
        Schema::dropIfExists('strands');
        Schema::dropIfExists('tracks');
        Schema::dropIfExists('academic_periods');
        Schema::dropIfExists('school_years');
        Schema::dropIfExists('users');
    }
};