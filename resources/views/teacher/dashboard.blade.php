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

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Class Schedules (2/3 width) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">My Classes</h2>
                    <a href="{{ route('teacher.grading.index') }}" class="text-green-600 hover:text-green-700 font-medium text-sm">View All</a>
                </div>
                <div class="p-6">
                    @if($classSchedules->isEmpty())
                        <p class="text-gray-600 text-center py-8">No classes assigned.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

        <!-- Right Column: Announcements (1/3 width) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 sticky top-6">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                        </svg>
                        Announcements
                    </h2>
                </div>
                <div class="p-6">
                    @if($announcements->count() > 0)
                        <div class="space-y-4">
                            @foreach($announcements as $announcement)
                            <div class="border-l-4 border-green-500 pl-4 py-2">
                                <h3 class="font-semibold text-gray-900 text-sm mb-1">{{ $announcement->title }}</h3>
                                <p class="text-xs text-gray-600 mb-2">{{ Str::limit($announcement->content, 100) }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500">{{ $announcement->published_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-gray-500 text-sm">No announcements yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
