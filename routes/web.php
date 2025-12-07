<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\AcademicStructureController;
use App\Http\Controllers\Admin\GradeManagementController;
use App\Http\Controllers\Teacher\GradingController;
use App\Http\Controllers\Teacher\AttendanceController;
use App\Http\Controllers\Student\GradesController;
use App\Http\Controllers\Student\AttendanceViewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

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

    // Academic Structure
    Route::resource('school-years', AcademicStructureController::class);
    Route::post('school-years/{schoolYear}/activate', [AcademicStructureController::class, 'activate'])->name('school-years.activate');
    Route::resource('strands', AcademicStructureController::class);
    Route::resource('sections', AcademicStructureController::class);
    Route::resource('subjects', AcademicStructureController::class);

    // Grade Management (Registrar Admin)
    Route::middleware('registrar-admin')->group(function () {
        Route::resource('grade-approval', GradeManagementController::class)->only(['index', 'show']);
        Route::post('grade-approval/{quarterlyGrade}/approve', [GradeManagementController::class, 'approve'])->name('grade-approval.approve');
        Route::post('grade-approval/{quarterlyGrade}/return', [GradeManagementController::class, 'return'])->name('grade-approval.return');
        Route::post('grade-approval/{quarterlyGrade}/override', [GradeManagementController::class, 'override'])->name('grade-approval.override');
    });
});

// Teacher Routes
Route::middleware(['auth', 'teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::resource('grading', GradingController::class)->only(['index', 'show', 'edit', 'update']);
    Route::post('grading/{classSchedule}/submit-grades', [GradingController::class, 'submitGrades'])->name('grading.submit');
    Route::resource('attendance', AttendanceController::class)->only(['index', 'create', 'store']);
    Route::get('class-roster/{classSchedule}', [AttendanceController::class, 'roster'])->name('attendance.roster');
});

// Student Routes
Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    Route::resource('grades', GradesController::class)->only(['index', 'show']);
    Route::resource('attendance', AttendanceViewController::class)->only(['index']);
    Route::get('announcements', function () {
        return view('student.announcements.index');
    })->name('announcements.index');
});

