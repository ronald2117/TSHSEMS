<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\AcademicStructureController;
use App\Http\Controllers\Admin\GradeManagementController;
use App\Http\Controllers\Admin\StudentManagementController;
use App\Http\Controllers\Admin\TeacherManagementController;
use App\Http\Controllers\Admin\TracksController;
use App\Http\Controllers\Admin\StrandsController;
use App\Http\Controllers\Admin\ClassSchedulesController;
use App\Http\Controllers\Admin\DocumentRequestController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\TechnicalAdminController;
use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Admin\AcademicPeriodController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\SuperAdminController;
use App\Http\Controllers\Teacher\GradingController;
use App\Http\Controllers\Teacher\AttendanceController;
use App\Http\Controllers\Teacher\AssessmentController as TeacherAssessmentController;
use App\Http\Controllers\Student\GradesController;
use App\Http\Controllers\Student\AttendanceViewController;
use App\Http\Controllers\Student\AnnouncementController as StudentAnnouncementController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Student\ScheduleController;
use App\Http\Controllers\Student\DocumentRequestController as StudentDocumentRequestController;
use App\Http\Controllers\Teacher\ClassesController;
use Illuminate\Support\Facades\Route;

// Maintenance mode page (accessible to all when maintenance is active)
Route::get('/maintenance', function () {
    $message = \App\Models\SystemSetting::getMaintenanceMessage();
    return view('maintenance', compact('message'));
})->name('maintenance.show');

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/help-support', function () {
    return view('help-support');
})->name('help-support');

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy-policy');

// Authentication Routes (NO PUBLIC REGISTRATION - Admin-only user creation)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

// Dashboard Route (Redirects based on role)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// Super Admin & Academic Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // User Management
    Route::resource('users', UserManagementController::class);
    Route::post('users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');

    // Student Management (with Profiles)
    Route::get('students', [StudentManagementController::class, 'index'])->name('students.index');
    Route::get('students/create', [StudentManagementController::class, 'create'])->name('students.create');
    Route::post('students', [StudentManagementController::class, 'store'])->name('students.store');
    Route::get('students/{student}', [StudentManagementController::class, 'show'])->name('students.show');
    Route::get('students/{student}/edit', [StudentManagementController::class, 'edit'])->name('students.edit');
    Route::put('students/{student}', [StudentManagementController::class, 'update'])->name('students.update');
    Route::delete('students/{student}', [StudentManagementController::class, 'destroy'])->name('students.destroy');
    Route::post('students/{student}/toggle-status', [StudentManagementController::class, 'toggleStatus'])->name('students.toggle-status');

    // Teacher Management (with Profiles)
    Route::get('teachers', [TeacherManagementController::class, 'index'])->name('teachers.index');
    Route::get('teachers/create', [TeacherManagementController::class, 'create'])->name('teachers.create');
    Route::post('teachers', [TeacherManagementController::class, 'store'])->name('teachers.store');
    Route::get('teachers/{teacher}', [TeacherManagementController::class, 'show'])->name('teachers.show');
    Route::get('teachers/{teacher}/edit', [TeacherManagementController::class, 'edit'])->name('teachers.edit');
    Route::put('teachers/{teacher}', [TeacherManagementController::class, 'update'])->name('teachers.update');
    Route::delete('teachers/{teacher}', [TeacherManagementController::class, 'destroy'])->name('teachers.destroy');
    Route::post('teachers/{teacher}/toggle-status', [TeacherManagementController::class, 'toggleStatus'])->name('teachers.toggle-status');

    // Academic Structure - School Years
    Route::resource('school-years', AcademicStructureController::class);
    Route::post('school-years/{schoolYear}/activate', [AcademicStructureController::class, 'activate'])->name('school-years.activate');
    
    // Academic Structure - Sections
    Route::get('sections', [AcademicStructureController::class, 'indexSections'])->name('sections.index');
    Route::get('sections/create', [AcademicStructureController::class, 'createSection'])->name('sections.create');
    Route::post('sections', [AcademicStructureController::class, 'storeSection'])->name('sections.store');
    Route::get('sections/{section}', [AcademicStructureController::class, 'showSection'])->name('sections.show');
    Route::get('sections/{section}/edit', [AcademicStructureController::class, 'editSection'])->name('sections.edit');
    Route::put('sections/{section}', [AcademicStructureController::class, 'updateSection'])->name('sections.update');
    Route::delete('sections/{section}', [AcademicStructureController::class, 'destroySection'])->name('sections.destroy');
    
    // Academic Structure - Tracks
    Route::resource('tracks', TracksController::class);
    
    // Academic Structure - Strands
    Route::resource('strands', StrandsController::class);
    
    // Academic Structure - Class Schedules
    Route::resource('class-schedules', ClassSchedulesController::class);
    Route::get('class-schedules/periods/{school_year_id}', [ClassSchedulesController::class, 'getAcademicPeriods'])->name('class-schedules.periods');
    
    // Academic Structure - Subjects
    Route::get('subjects', [AcademicStructureController::class, 'indexSubjects'])->name('subjects.index');
    Route::get('subjects/create', [AcademicStructureController::class, 'createSubject'])->name('subjects.create');
    Route::post('subjects', [AcademicStructureController::class, 'storeSubject'])->name('subjects.store');
    Route::get('subjects/{subject}', [AcademicStructureController::class, 'showSubject'])->name('subjects.show');
    Route::get('subjects/{subject}/edit', [AcademicStructureController::class, 'editSubject'])->name('subjects.edit');
    Route::put('subjects/{subject}', [AcademicStructureController::class, 'updateSubject'])->name('subjects.update');
    Route::delete('subjects/{subject}', [AcademicStructureController::class, 'destroySubject'])->name('subjects.destroy');

    // Announcements Management (Super Admin & Academic Admin)
    Route::resource('announcements', AdminAnnouncementController::class);

    // Academic Periods Management
    Route::resource('academic-periods', AcademicPeriodController::class);
    Route::post('academic-periods/{academicPeriod}/toggle-status', [AcademicPeriodController::class, 'toggleStatus'])->name('academic-periods.toggle-status');

    // Reports & Analytics
    Route::get('reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('reports/students', [ReportsController::class, 'studentList'])->name('reports.students');
    Route::get('reports/grades', [ReportsController::class, 'gradesSummary'])->name('reports.grades');
    Route::get('reports/attendance', [ReportsController::class, 'attendanceSummary'])->name('reports.attendance');
    Route::get('reports/form137/{student}', [ReportsController::class, 'form137'])->name('reports.form137');
    Route::get('reports/form138/{student}', [ReportsController::class, 'form138'])->name('reports.form138');
    Route::get('reports/master-list/{section}', [ReportsController::class, 'masterList'])->name('reports.master-list');

    // Grade Management (Registrar Admin)
    Route::middleware('registrar-admin')->group(function () {
        Route::resource('grade-approval', GradeManagementController::class)->only(['index', 'show']);
        Route::post('grade-approval/{quarterlyGrade}/approve', [GradeManagementController::class, 'approve'])->name('grade-approval.approve');
        Route::post('grade-approval/{quarterlyGrade}/return', [GradeManagementController::class, 'return'])->name('grade-approval.return');
        Route::post('grade-approval/{quarterlyGrade}/override', [GradeManagementController::class, 'override'])->name('grade-approval.override');
        
        // Document Requests
        Route::get('documents', [DocumentRequestController::class, 'index'])->name('documents.index');
        Route::get('documents/{documentRequest}', [DocumentRequestController::class, 'show'])->name('documents.show');
        Route::put('documents/{documentRequest}/status', [DocumentRequestController::class, 'updateStatus'])->name('documents.update-status');
        
        // Enrollment Management
        Route::get('enrollment', [EnrollmentController::class, 'index'])->name('enrollment.index');
        Route::get('enrollment/{student}/enroll', [EnrollmentController::class, 'enroll'])->name('enrollment.enroll');
        Route::post('enrollment/{student}', [EnrollmentController::class, 'processEnrollment'])->name('enrollment.process');
        Route::get('enrollment/{student}/transfer', [EnrollmentController::class, 'showTransfer'])->name('enrollment.transfer');
        Route::post('enrollment/{student}/transfer', [EnrollmentController::class, 'processTransfer'])->name('enrollment.transfer.process');
        Route::delete('enrollment/{student}/unenroll', [EnrollmentController::class, 'unenroll'])->name('enrollment.unenroll');
        Route::get('enrollment/history', [EnrollmentController::class, 'history'])->name('enrollment.history');
        Route::get('enrollment/bulk-import', [EnrollmentController::class, 'bulkImportForm'])->name('enrollment.bulk-import');
        Route::post('enrollment/bulk-import', [EnrollmentController::class, 'processBulkImport'])->name('enrollment.bulk-import.process');
    });
    
    // Technical Admin Routes
    Route::middleware('technical-admin')->group(function () {
        // Database Backups
        Route::get('backups', [TechnicalAdminController::class, 'backupsIndex'])->name('backups.index');
        Route::post('backups', [TechnicalAdminController::class, 'createBackup'])->name('backups.create');
        Route::get('backups/{filename}/download', [TechnicalAdminController::class, 'downloadBackup'])->name('backups.download');
        Route::delete('backups/{filename}', [TechnicalAdminController::class, 'deleteBackup'])->name('backups.delete');
        
        // Activity Logs
        Route::get('logs/activity', [TechnicalAdminController::class, 'activityLogs'])->name('logs.activity');
        Route::get('logs/grades', [TechnicalAdminController::class, 'gradeAuditLogs'])->name('logs.grades');
        
        // User Management Tools
        Route::get('users/{user}/reset-password', [TechnicalAdminController::class, 'passwordResetForm'])->name('users.reset-password');
        Route::post('users/{user}/reset-password', [TechnicalAdminController::class, 'resetPassword'])->name('users.reset-password.process');
        
        // System Stats (shared with Super Admin)
        Route::get('system/stats', [TechnicalAdminController::class, 'systemStats'])->name('system.stats');
    });
    
    // Super Admin Routes (Override access to all admin functions)
    Route::middleware('super-admin')->group(function () {
        Route::get('all-navigations', [SuperAdminController::class, 'allAdminNavigations'])->name('all-navigations');
        
        // Global System Settings
        Route::get('settings', [SuperAdminController::class, 'systemSettings'])->name('settings.index');
        Route::put('settings', [SuperAdminController::class, 'updateSystemSettings'])->name('settings.update');
        
        // Feature Toggles
        Route::get('features', [SuperAdminController::class, 'featureToggles'])->name('features.index');
        Route::post('features/toggle', [SuperAdminController::class, 'toggleFeature'])->name('features.toggle');
        
        // Maintenance Mode
        Route::get('maintenance', [SuperAdminController::class, 'maintenanceMode'])->name('maintenance.index');
        Route::post('maintenance/toggle', [SuperAdminController::class, 'toggleMaintenance'])->name('maintenance.toggle');
        
        // Academic Year Locking
        Route::get('year-locking', [SuperAdminController::class, 'yearLocking'])->name('year-locking.index');
        Route::post('year-locking/{schoolYear}/toggle', [SuperAdminController::class, 'toggleYearLock'])->name('year-locking.toggle');
        
        // Security & Audit Logs
        Route::get('audit/activity', [SuperAdminController::class, 'activityLogs'])->name('audit.activity');
        Route::get('audit/login', [SuperAdminController::class, 'loginLogs'])->name('audit.login');
        Route::get('audit/grades', [SuperAdminController::class, 'gradeAuditLogs'])->name('audit.grades');
    });
});

// Teacher Routes
Route::middleware(['auth', 'teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    // Profile Management
    Route::get('profile', [\App\Http\Controllers\Teacher\ProfileController::class, 'index'])->name('profile.index');
    Route::get('profile/edit', [\App\Http\Controllers\Teacher\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [\App\Http\Controllers\Teacher\ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [\App\Http\Controllers\Teacher\ProfileController::class, 'updatePassword'])->name('profile.password');
    
    // Classes Management
    Route::get('classes', [ClassesController::class, 'index'])->name('classes.index');
    Route::get('classes/{classSchedule}', [ClassesController::class, 'show'])->name('classes.show');
    Route::get('classes/{classSchedule}/roster', [ClassesController::class, 'roster'])->name('classes.roster');
    
    // Grading
    Route::resource('grading', GradingController::class)->only(['index', 'show', 'edit', 'update']);
    Route::post('grading/{classSchedule}/submit-grades', [GradingController::class, 'submitGrades'])->name('grading.submit');
    Route::post('grading/{classSchedule}/submit-grade/{student}', [GradingController::class, 'submitGrade'])->name('grading.submit-grade');
    Route::patch('grading/unsubmit-grade/{grade}', [GradingController::class, 'unsubmitGrade'])->name('grading.unsubmit-grade');
    
    // Assessments Management
    Route::resource('assessments', TeacherAssessmentController::class);
    Route::post('assessments/{assessment}/toggle-publish', [TeacherAssessmentController::class, 'togglePublish'])->name('assessments.toggle-publish');
    
    // Attendance
    Route::resource('attendance', AttendanceController::class)->only(['index', 'create', 'store']);
    Route::get('class-roster/{classSchedule}', [AttendanceController::class, 'roster'])->name('attendance.roster');
});

// Student Routes
Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    // Profile
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    
    // Grades
    Route::resource('grades', GradesController::class)->only(['index', 'show']);
    
    // Attendance
    Route::resource('attendance', AttendanceViewController::class)->only(['index']);
    
    // Schedule
    Route::get('schedule', [ScheduleController::class, 'index'])->name('schedule.index');
    
    // Announcements
    Route::get('announcements', [StudentAnnouncementController::class, 'index'])->name('announcements.index');
    
    // Document Requests
    Route::resource('documents', StudentDocumentRequestController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('documents/{document}/cancel', [StudentDocumentRequestController::class, 'cancel'])->name('documents.cancel');
});

