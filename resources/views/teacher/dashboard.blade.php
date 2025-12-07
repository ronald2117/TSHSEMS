@extends('layouts.app')

@section('page_title', 'Teacher Dashboard')
@section('page_subtitle', 'Manage your classes and grades.')

@section('content')
<div class="p-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Classes</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $classSchedules->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.998 10-10.747S17.5 6.253 12 6.253z"></path>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0H9m3-8v13m0 0H9m6 0h.01M9 16h.01"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Pending Submissions</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $pendingGrades }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
                            <p>📚 {{ $schedule->enrollments()->count() }} Students</p>
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
