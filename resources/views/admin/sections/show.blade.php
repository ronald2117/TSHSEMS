@extends('layouts.app')

@section('page_title', 'Section Details')
@section('page_subtitle', 'View and manage section information')

@section('toolbar')
    <div class="flex items-center justify-end gap-3 w-full">
        <a href="{{ route('admin.sections.index') }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Sections
        </a>
    </div>
@endsection

@section('content')
<div class="p-6">
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <!-- Section Profile Card -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Header Section -->
        <div class="px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div>
                        <h2 class="text-gray-900 text-2xl font-bold">{{ $section->name }}</h2>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="text-gray-900 px-3 py-1 bg-blue-100 rounded-full text-sm">
                                {{ $section->strand->code }}
                            </span>
                            <span class="text-white px-3 py-1 bg-green-700 rounded-full text-sm">
                                {{ $section->schoolYear->name }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Action Icons -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.sections.edit', $section) }}" 
                       class="p-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition shadow-sm" 
                       title="Edit Section">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>

                    <form method="POST" action="{{ route('admin.sections.destroy', $section) }}" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete this section? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition shadow-sm" 
                                title="Delete Section">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Section Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Section Information</h3>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-600">Section Name</label>
                        <p class="text-gray-900 mt-1">{{ $section->name }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Grade Level</label>
                        <p class="text-gray-900 mt-1">Grade {{ $section->grade_level }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Strand</label>
                        <p class="text-gray-900 mt-1">{{ $section->strand->name }} ({{ $section->strand->code }})</p>
                    </div>
                </div>

                <!-- Adviser & Capacity Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Adviser & Capacity</h3>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-600">Adviser</label>
                        @if($section->adviser)
                            <p class="text-gray-900 mt-1">{{ $section->adviser->full_name }}</p>
                        @else
                            <p class="text-gray-500 mt-1 italic">Not assigned</p>
                        @endif
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Maximum Students</label>
                        <p class="text-gray-900 mt-1">{{ $section->max_students ?? 'Not set' }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Current Enrollment</label>
                        <p class="text-gray-900 mt-1">
                            {{ $section->student_count }} student{{ $section->student_count != 1 ? 's' : '' }}
                            @if($section->max_students)
                                <span class="text-sm text-gray-500">({{ $section->available_slots }} slot{{ $section->available_slots != 1 ? 's' : '' }} available)</span>
                            @endif
                        </p>
                    </div>

                </div>
            </div>
        </div>

        <!-- Students Enrolled -->
        @if($section->studentProfiles && count($section->studentProfiles) > 0)
        <div class="px-8 py-6 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Enrolled Students ({{ count($section->studentProfiles) }})</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($section->studentProfiles as $studentProfile)
                    @if($studentProfile->user)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-green-300 hover:shadow-sm transition">
                        <div class="flex items-center space-x-3">
                            @if($studentProfile->user->avatar_path && file_exists(public_path('storage/' . $studentProfile->user->avatar_path)))
                                <img src="{{ asset('storage/' . $studentProfile->user->avatar_path) }}" alt="{{ $studentProfile->user->full_name }}" class="w-10 h-10 rounded-full object-cover">
                            @else
                                <div class="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center">
                                    <span class="text-white text-sm">{{ strtoupper(substr($studentProfile->user->first_name, 0, 1)) }}{{ strtoupper(substr($studentProfile->user->last_name, 0, 1)) }}</span>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 truncate">{{ $studentProfile->user->full_name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $studentProfile->lrn }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
        @else
        <div class="px-8 py-6 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Enrolled Students</h3>
            <p class="text-gray-600">No students enrolled yet.</p>
        </div>
        @endif

        <!-- Subject Schedules -->
        @if($section->classSchedules && count($section->classSchedules) > 0)
        <div class="px-8 py-6 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Subject Schedules ({{ count($section->classSchedules) }})</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($section->classSchedules as $schedule)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-green-300 hover:shadow-sm transition">
                        <div class="mb-3">
                            <h4 class="font-semibold text-gray-900">{{ $schedule->subject->name }}</h4>
                            <p class="text-sm text-gray-600">{{ $schedule->subject->code }}</p>
                        </div>
                        @if($schedule->teacher)
                        <div class="flex items-center space-x-2 mb-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <p class="text-xs text-gray-600">{{ $schedule->teacher->full_name }}</p>
                        </div>
                        @endif
                        @if($schedule->room)
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <p class="text-xs text-gray-600">Room {{ $schedule->room }}</p>
                        </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="px-8 py-6 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Subject Schedules</h3>
            <p class="text-gray-600">No subjects scheduled yet.</p>
        </div>
        @endif
    </div>
</div>
@endsection
