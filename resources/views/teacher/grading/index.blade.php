@extends('layouts.app')

@section('page_title', 'Teacher Grading')
@section('page_subtitle', 'Manage student grades and assessments.')

@section('content')
<div class="p-6">
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($classSchedules as $schedule)
        <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-900">{{ $schedule->subject->name }}</h3>
                <p class="text-sm text-gray-600">{{ $schedule->section->name }} - Grade {{ $schedule->section->grade_level }}</p>
            </div>

            <div class="space-y-2 text-sm text-gray-600 mb-6">
                <p class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    {{ $schedule->room ?? 'TBA' }}
                </p>
                <p class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $schedule->schedule_time ?? 'TBA' }}
                </p>
                <p class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    {{ $schedule->enrollments()->count() }} Students
                </p>
            </div>

            <div class="space-y-2">
                <a href="{{ route('teacher.grading.show', $schedule) }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-medium text-sm transition">
                    View Grades
                </a>
                <a href="{{ route('teacher.grading.edit', $schedule) }}" class="block w-full text-center bg-primary-600 hover:bg-primary-700 text-white py-2 rounded-lg font-medium text-sm transition">
                    Enter Scores
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-xl shadow-sm p-12 text-center">
            <p class="text-gray-600">No classes assigned yet.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
