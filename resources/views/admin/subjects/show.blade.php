@extends('layouts.app')
@section('page_title', 'Subject Details')
@section('page_subtitle', 'View subject information and class schedules.')

@section('toolbar')
    <div class="flex items-center justify-end gap-3 w-full">
        <a href="{{ route('admin.subjects.index') }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Subjects
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

    <!-- Subject Profile Card -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <!-- Header Section -->
        <div class="px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h2 class="text-gray-900 text-2xl font-bold">{{ $subject->code }}</h2>
                    <p class="text-gray-600 text-lg mt-1">{{ $subject->name }}</p>
                    <div class="flex items-center gap-3 mt-3">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                            @if($subject->subject_type === 'core') bg-blue-100 text-blue-800
                            @elseif($subject->subject_type === 'applied') bg-green-100 text-green-800
                            @elseif($subject->subject_type === 'specialized') bg-purple-100 text-purple-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($subject->subject_type) }}
                        </span>
                        <span class="text-sm text-gray-600">{{ $subject->units }} {{ Str::plural('unit', $subject->units) }}</span>
                    </div>
                </div>

                <!-- Action Icons -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.subjects.edit', $subject) }}" 
                       class="p-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition shadow-sm" 
                       title="Edit Subject">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>

                    <form method="POST" action="{{ route('admin.subjects.destroy', $subject) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this subject?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition shadow-sm" 
                                title="Delete Subject">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Details Section -->
        @if($subject->description)
        <div class="px-8 py-4 bg-gray-50 border-y border-gray-200">
            <label class="text-sm font-medium text-gray-600">Description</label>
            <p class="text-gray-900 mt-1">{{ $subject->description }}</p>
        </div>
        @endif
    </div>

    <!-- Class Schedules Section -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Class Schedules ({{ $subject->classSchedules->count() }})</h3>
            </div>
        </div>

        @if($subject->classSchedules->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Section</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Teacher</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Period</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Schedule</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($subject->classSchedules as $schedule)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-900">{{ $schedule->section->name }}</span>
                                    <p class="text-xs text-gray-600 mt-1">{{ $schedule->section->strand->code }} - Grade {{ $schedule->section->grade_level }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-900">{{ $schedule->teacher->full_name }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <span class="text-sm text-gray-900">{{ $schedule->academicPeriod->name }}</span>
                                    <p class="text-xs text-gray-600 mt-1">{{ $schedule->academicPeriod->schoolYear->name }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600">
                                    @if($schedule->schedule_time)
                                        <p>{{ $schedule->schedule_time }}</p>
                                    @else
                                        <span class="text-gray-400">Not set</span>
                                    @endif
                                    @if($schedule->room)
                                        <p class="text-xs text-gray-500 mt-1">Room: {{ $schedule->room }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    <a href="{{ route('admin.class-schedules.show', $schedule) }}" 
                                       class="text-gray-600 hover:text-gray-700 transition mb-1" 
                                       title="View Details">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12 text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p>No class schedules for this subject yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection
