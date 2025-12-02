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
            $table->comment('Multi-role authentication for students, teachers, and admins');
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
            
            $table->index('last_login_at');
            $table->index(['role', 'is_active']);
        });

        // ==========================================
        // 2. ACADEMIC SETUP
        // ==========================================

        Schema::create('school_years', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "2025-2026"
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            
            $table->unique('name');
            $table->unique(['start_date', 'end_date']);
        });

        Schema::create('academic_periods', function (Blueprint $table) {
            $table->comment('Semesters or grading periods within a school year');
            $table->id();
            $table->foreignId('school_year_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // e.g., "1st Semester"
            $table->enum('status', ['Active', 'Closed'])->default('Active');
            $table->timestamps();
        });

        Schema::create('tracks', function (Blueprint $table) {
            $table->comment('SHS tracks: Academic, TVL, Sports, Arts & Design');
            $table->id();
            $table->string('code')->unique(); // e.g., "ACAD", "TVL"
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('strands', function (Blueprint $table) {
            $table->comment('Specializations under each track (e.g., STEM, ABM, HUMSS)');
            $table->id();
            $table->foreignId('track_id')->constrained()->cascadeOnDelete();
            $table->string('code')->unique(); // e.g., "STEM", "ABM"
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('sections', function (Blueprint $table) {
            $table->comment('Class sections assigned to specific strands and grade levels');
            $table->id();
            $table->foreignId('school_year_id')->constrained();
            $table->string('name'); // e.g., "Diamond"
            $table->integer('grade_level')->unsigned()->comment('11 or 12 only'); // 11 or 12
            $table->foreignId('strand_id')->constrained();
            $table->foreignId('adviser_id')->nullable()->constrained('users');
            $table->integer('max_students')->unsigned()->default(40);
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['school_year_id', 'name', 'grade_level']);
        });

        // ==========================================
        // 3. USER PROFILES (Details)
        // ==========================================

        Schema::create('student_profiles', function (Blueprint $table) {
            $table->comment('Extended profile information for student users');
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('lrn')->unique(); // Learner Reference Number
            $table->foreignId('current_section_id')->nullable()->constrained('sections');
            $table->foreignId('strand_id')->constrained();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_contact')->nullable();
            $table->json('emergency_contacts')->nullable()->comment('Array of emergency contact details');
            $table->date('birthdate')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
            
            $table->index('lrn');
            $table->index('current_section_id');
        });

        Schema::create('teacher_profiles', function (Blueprint $table) {
            $table->comment('Extended profile information for teacher users');
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
            $table->comment('Subject catalog with codes and types');
            $table->id();
            $table->string('code')->unique(); // e.g., "GENMATH"
            $table->string('name');
            $table->enum('type', ['Core', 'Applied', 'Specialized']);
            $table->integer('units')->unsigned()->default(1);
            $table->boolean('is_active')->default(true);
            $table->json('learning_competencies')->nullable()->comment('DepEd learning competencies');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('strand_subjects', function (Blueprint $table) {
            $table->comment('Links subjects to strands with grade level and semester requirements');
            $table->id();
            $table->foreignId('strand_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->integer('grade_level')->unsigned()->comment('11 or 12');
            $table->enum('semester', ['1st', '2nd'])->nullable();
            $table->boolean('is_required')->default(true);
            $table->timestamps();
            
            $table->unique(['strand_id', 'subject_id', 'grade_level']);
        });

        Schema::create('class_schedules', function (Blueprint $table) {
            $table->comment('Teacher assignments to sections for specific subjects');
            $table->id();
            $table->foreignId('section_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained();
            $table->foreignId('teacher_id')->constrained('users');
            $table->foreignId('academic_period_id')->constrained(); // Links to Semester
            $table->string('schedule_time')->nullable(); // e.g., "MWF 8:00-9:00"
            $table->string('room')->nullable();
            $table->json('schedule_details')->nullable()->comment('Detailed schedule: days, times, room changes');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['section_id', 'subject_id', 'academic_period_id']);
            $table->index(['academic_period_id', 'section_id']);
            $table->index('teacher_id');
        });

        // Enrollment Table: Links Student -> Class Schedule
        Schema::create('student_subject_enrollments', function (Blueprint $table) {
            $table->comment('Student enrollment in specific class schedules');
            $table->id();
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('class_schedule_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['enrolled', 'dropped'])->default('enrolled');
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['student_id', 'class_schedule_id']);
        });

        // Enrollment History: Tracks which section a student belonged to historically
        Schema::create('student_enrollment_history', function (Blueprint $table) {
            $table->comment('Historical record of student section assignments');
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
            $table->comment('DepEd-compliant grading weights by subject type');
            $table->id();
            $table->enum('subject_type', ['Core', 'Applied', 'Specialized'])->unique();
            $table->decimal('written_weight', 3, 2)->comment('Must sum to 1.00 with performance and exam'); // 0.25
            $table->decimal('performance_weight', 3, 2); // 0.50
            $table->decimal('exam_weight', 3, 2); // 0.25
            $table->timestamps();
        });

        Schema::create('grade_transmutations', function (Blueprint $table) {
            $table->comment('Conversion table from percentage scores to transmuted grades');
            $table->id();
            $table->decimal('min_score', 5, 2); 
            $table->decimal('max_score', 5, 2);
            $table->integer('transmuted_grade'); // 60-100
            $table->timestamps();
        });

        Schema::create('assessments', function (Blueprint $table) {
            $table->comment('Graded activities and exams for each class');
            $table->id();
            $table->foreignId('class_schedule_id')->constrained()->cascadeOnDelete();
            $table->string('title'); // "Quiz #1", "PT #2"
            $table->enum('type', ['written_work', 'performance_task', 'quarterly_assessment']);
            $table->integer('max_score')->unsigned();
            $table->integer('quarter')->unsigned()->comment('1-4 only');
            $table->boolean('is_published')->default(false); // Teacher visibility toggle
            $table->date('assessment_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('student_scores', function (Blueprint $table) {
            $table->comment('Individual student scores for each assessment');
            $table->id();
            $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users');
            $table->decimal('score', 5, 2);
            $table->timestamps();
            
            $table->unique(['student_id', 'assessment_id']);
            $table->index(['student_id', 'assessment_id']);
        });

        Schema::create('quarterly_grades', function (Blueprint $table) {
            $table->comment('Final computed grades per quarter with approval workflow');
            $table->id();
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('class_schedule_id')->constrained();
            $table->integer('quarter')->unsigned()->comment('1-4 only');
            $table->decimal('initial_grade', 5, 2); // The computed raw score
            $table->integer('final_grade'); // The transmuted grade (75, 80, etc)
            $table->string('remarks')->nullable(); // Passed/Failed
            
            // Workflow Status for Registrar Approval
            $table->enum('status', ['Draft', 'Submitted', 'Approved', 'Returned'])->default('Draft');
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('submitted_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->text('return_reason')->nullable()->comment('Reason when status is Returned');
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['student_id', 'class_schedule_id', 'quarter']);
            $table->index(['student_id', 'quarter']);
            $table->index('status');
        });

        Schema::create('grade_audit_logs', function (Blueprint $table) {
            $table->comment('Complete audit trail of all grade changes');
            $table->id();
            $table->foreignId('quarterly_grade_id')->constrained('quarterly_grades')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->decimal('old_grade', 5, 2)->nullable(); // Allow nulls for initial entries
            $table->decimal('new_grade', 5, 2);
            $table->string('field_changed')->default('final_grade')->comment('Track what field changed'); // Track what changed
            $table->string('reason');
            $table->string('ip_address')->nullable();
            $table->timestamps();
    
            $table->index(['quarterly_grade_id', 'created_at']);
        });

        Schema::create('student_gwa_cache', function (Blueprint $table) {

        Schema::create('attendances', function (Blueprint $table) {
            $table->comment('Daily attendance records for students in each class');
            $table->id();
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('class_schedule_id')->constrained();
            $table->date('date');
            $table->enum('status', ['Present', 'Absent', 'Late', 'Excused']);
            $table->string('remarks')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->comment('Teacher who recorded');
            $table->timestamps();

            $table->unique(['student_id', 'class_schedule_id', 'date']);
            $table->index(['student_id', 'date']);
            $table->index('class_schedule_id');
        });

        Schema::create('document_requests', function (Blueprint $table) {
            $table->comment('Student document requests and processing status');
            $table->id();
            $table->foreignId('student_id')->constrained('users');
            $table->string('type'); // "Form 137", "Certificate of Enrollment"
            $table->enum('status', ['Pending', 'Processing', 'Ready', 'Claimed', 'Rejected'])->default('Pending');
            $table->integer('copies')->unsigned()->default(1)->comment('Number of copies requested');
            $table->string('purpose')->nullable()->comment('Reason for request');
            $table->decimal('fee', 8, 2)->nullable()->comment('Document processing fee');
            $table->foreignId('processed_by')->nullable()->constrained('users');
            $table->timestamp('claimed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            
            $table->index(['student_id', 'status']);
        });

        Schema::create('announcements', function (Blueprint $table) {
            $table->comment('System-wide announcements with role-based targeting');
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->boolean('is_public')->default(false); // For Landing Page
            $table->foreignId('author_id')->constrained('users');
            $table->string('target_role')->nullable(); // "student", "teacher", or null for all
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();
            
            $table->index(['target_role', 'published_at']);
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->comment('System-wide activity and security audit log');
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
        Schema::dropIfExists('student_gwa_cache');
        Schema::dropIfExists('grade_audit_logs');
        Schema::dropIfExists('quarterly_grades');
        Schema::dropIfExists('student_scores');
        Schema::dropIfExists('assessments');
        Schema::dropIfExists('grade_transmutations');
        Schema::dropIfExists('grading_components');
        Schema::dropIfExists('student_enrollment_history');
        Schema::dropIfExists('student_subject_enrollments');
        Schema::dropIfExists('class_schedules');
        Schema::dropIfExists('strand_subjects');
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