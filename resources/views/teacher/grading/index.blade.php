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
                <p>📍 Room: {{ $schedule->room ?? 'TBA' }}</p>
                <p>🕐 Time: {{ $schedule->schedule_time ?? 'TBA' }}</p>
                <p>👥 Students: {{ $schedule->enrollments()->count() }}</p>
            </div>

            <div class="space-y-2">
                <a href="{{ route('teacher.grading.show', $schedule) }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-medium text-sm transition">
                    View Grades
                </a>
                <a href="{{ route('teacher.grading.edit', $schedule) }}" class="block w-full text-center bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-medium text-sm transition">
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
