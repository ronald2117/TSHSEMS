@extends('layouts.app')

@section('page_title', 'Teacher Dashboard')
@section('page_subtitle', 'Manage your classes and grades.')

@section('content')
<div class="p-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Classes</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalClasses }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.343 5.445 6 6.943 6 10c0 3.314 2.686 6 6 6s6-2.686 6-6c0-3.057-4.343-4.555-6-3.747z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Students</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalStudents }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21.11a4 4 0 110-5.292m-6 5.292a4 4 0 110-5.292m4.125-4.355A3.015 3.015 0 0015 12m-9 0a3.015 3.015 0 001.875-.645M12 12v.01"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Draft Grades</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $pendingGrades }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Assessments</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalAssessments }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Class Schedules -->
    <div class="bg-white rounded-xl shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">My Classes</h2>
            <a href="{{ route('teacher.grading.index') }}" class="text-green-600 hover:text-green-700 font-medium text-sm">View All</a>
        </div>
        <div class="p-6">
            @if($classSchedules->isEmpty())
                <p class="text-gray-600 text-center py-8">No classes assigned.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($classSchedules as $schedule)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $schedule->subject->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $schedule->section->name }} - Grade {{ $schedule->section->grade_level }}</p>
                            </div>
                        </div>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <p>📍 {{ $schedule->room ?? 'TBA' }}</p>
                            <p>🕐 {{ $schedule->schedule_time ?? 'TBA' }}</p>
                            <p>📚 {{ $schedule->enrollments->count() }} Students</p>
                        </div>
                        <a href="{{ route('teacher.grading.show', $schedule) }}" class="block text-center bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-medium transition text-sm">
                            Manage Grades
                        </a>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
