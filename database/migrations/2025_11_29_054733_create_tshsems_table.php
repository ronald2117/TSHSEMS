<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ==========================================
        // MODULE A: IDENTITY
        // ==========================================

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('login_id')->unique()->nullable(); // Student Number (e.g., 2025-0001) or Employee ID
            $table->string('email')->unique();
            $table->string('password');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('avatar_path')->nullable();
            $table->boolean('is_active')->default(true); // For account deactivation
            $table->rememberToken();
            $table->timestamps();
        });

        // ==========================================
        // MODULE B: ACADEMIC SETUP
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
            $table->string('code'); // e.g., "ACAD"
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('strands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('track_id')->constrained()->cascadeOnDelete();
            $table->string('code'); // e.g., "STEM"
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
        });

        // ==========================================
        // MODULE C: PROFILES & HISTORY
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
            $table->timestamps();
        });

        Schema::create('student_enrollment_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('section_id')->constrained();
            $table->foreignId('academic_period_id')->constrained();
            $table->integer('grade_level');
            $table->string('status'); // Enrolled, Dropped, Transferred
            $table->timestamps();
        });

        Schema::create('teacher_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('department');
            $table->timestamps();
        });

        // ==========================================
        // MODULE D: ENROLLMENT & SCHEDULES
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
            $table->foreignId('academic_period_id')->constrained();
            $table->string('schedule_time')->nullable(); // e.g., "MWF 8-9AM"
            $table->string('room')->nullable();
            $table->timestamps();
        });

        Schema::create('student_subject_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('class_schedule_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['enrolled', 'dropped'])->default('enrolled');
            $table->timestamps();
        });

        // ==========================================
        // MODULE E: GRADING ENGINE (DepEd Compliant)
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
            $table->decimal('min_score', 5, 2); // 60.00
            $table->decimal('max_score', 5, 2); // 61.59
            $table->integer('transmuted_grade'); // 75
            $table->timestamps();
        });

        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_schedule_id')->constrained()->cascadeOnDelete();
            $table->string('title'); // "Quiz #1"
            $table->enum('type', ['written_work', 'performance_task', 'quarterly_assessment']);
            $table->integer('max_score');
            $table->integer('quarter'); // 1-4
            $table->boolean('is_published')->default(false); // Teacher control
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
            $table->decimal('initial_grade', 5, 2); // Raw
            $table->integer('final_grade'); // Transmuted
            $table->string('remarks')->nullable(); // Passed/Failed
            $table->enum('status', ['Draft', 'Submitted', 'Approved'])->default('Draft');
            $table->timestamps();
        });

        Schema::create('grade_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quarterly_grade_id')->constrained('quarterly_grades')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users'); // Who changed it
            $table->integer('old_grade');
            $table->integer('new_grade');
            $table->string('reason');
            $table->timestamps();
        });

        // ==========================================
        // MODULE F: REGISTRAR & OPERATIONS
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

        // UPDATED: Added Pickup Verification Fields
        Schema::create('document_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users');
            $table->string('type'); // Form 137, ID, Report Card
            $table->string('status'); // Pending, Ready, Claimed
            $table->string('request_slip_no')->nullable(); // The PIN Code
            $table->string('digital_signature_path')->nullable(); // For verification
            $table->timestamp('claimed_at')->nullable(); // When picked up
            $table->foreignId('processed_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->foreignId('author_id')->constrained('users');
            $table->string('target_role')->nullable(); // e.g., "students"
            $table->foreignId('target_section_id')->nullable()->constrained('sections');
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('type'); // Exam, Holiday
            $table->string('audience_type'); // all, teachers, students, specific_section
            $table->foreignId('section_id')->nullable()->constrained('sections');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // ==========================================
        // MODULE G: SYSTEM ADMIN
        // ==========================================

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->string('action');
            $table->text('description');
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });

        Schema::create('backups', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('status');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        Schema::create('generated_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('type');
            $table->string('file_path');
            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        // Drop in reverse order to handle Foreign Key constraints safely
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('generated_reports');
        Schema::dropIfExists('backups');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('events');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('document_requests');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('grade_audit_logs');
        Schema::dropIfExists('quarterly_grades');
        Schema::dropIfExists('student_scores');
        Schema::dropIfExists('assessments');
        Schema::dropIfExists('grade_transmutations');
        Schema::dropIfExists('grading_components');
        Schema::dropIfExists('student_subject_enrollments');
        Schema::dropIfExists('class_schedules');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('teacher_profiles');
        Schema::dropIfExists('student_enrollment_history');
        Schema::dropIfExists('student_profiles');
        Schema::dropIfExists('sections');
        Schema::dropIfExists('strands');
        Schema::dropIfExists('tracks');
        Schema::dropIfExists('academic_periods');
        Schema::dropIfExists('school_years');
        Schema::dropIfExists('users');
    }
};
