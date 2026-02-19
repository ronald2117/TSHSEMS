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
    <div class="max-w-4xl mx-auto">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-primary-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Subject Information Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $subject->code }}</h3>
                        @if($subject->is_active)
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Active
                            </span>
                        @else
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                Inactive
                            </span>
                        @endif
                    </div>
                    <p class="text-lg text-gray-600">{{ $subject->name }}</p>
                </div>

                <!-- Action Buttons -->
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

            <!-- Subject Details Grid -->
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Subject Code</h4>
                    <p class="text-lg font-semibold text-gray-900">{{ $subject->code }}</p>
                </div>

                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Subject ID</h4>
                    <p class="text-lg font-semibold text-gray-900">#{{ $subject->id }}</p>
                </div>

                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Subject Name</h4>
                    <p class="text-lg font-semibold text-gray-900">{{ $subject->name }}</p>
                </div>

                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Units</h4>
                    <p class="text-lg font-semibold text-gray-900">{{ $subject->units }} {{ Str::plural('unit', $subject->units) }}</p>
                </div>

                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Subject Type</h4>
                    <p class="text-lg font-semibold">
                        <span class="px-3 py-1 text-xs rounded-full 
                            @if(strtolower($subject->type) === 'core') bg-blue-100 text-blue-800
                            @elseif(strtolower($subject->type) === 'applied') bg-green-100 text-green-800
                            @elseif(strtolower($subject->type) === 'specialized') bg-purple-100 text-purple-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($subject->type) }}
                        </span>
                    </p>
                </div>

                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Status</h4>
                    <p class="text-lg font-semibold">
                        @if($subject->is_active)
                            <span class="text-primary-600">Active</span>
                        @else
                            <span class="text-gray-600">Inactive</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Learning Competencies Card -->
        @if($subject->learning_competencies && count($subject->learning_competencies) > 0)
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Learning Competencies</h3>
            <div class="space-y-2">
                @foreach($subject->learning_competencies as $index => $competency)
                <div class="flex items-start gap-3 p-3 bg-blue-50 rounded-lg">
                    <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center bg-blue-600 text-white text-xs font-semibold rounded-full">
                        {{ $index + 1 }}
                    </span>
                    <p class="text-sm text-gray-900 flex-1">{{ $competency }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Strands Using This Subject -->
        @if($subject->strands->count() > 0)
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Strands Using This Subject</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($subject->strands as $strand)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $strand->name }}</p>
                        <p class="text-xs text-gray-500">
                            Grade {{ $strand->pivot->grade_level }} • {{ $strand->pivot->semester }} • 
                            {{ $strand->pivot->is_required ? 'Required' : 'Elective' }}
                        </p>
                    </div>
                    <span class="text-xs font-medium text-blue-600">
                        {{ $strand->code }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Timestamps Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Record Information</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Created At:</span>
                    <span class="text-sm text-gray-900">{{ $subject->created_at->format('M d, Y h:i A') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Last Updated:</span>
                    <span class="text-sm text-gray-900">{{ $subject->updated_at->format('M d, Y h:i A') }}</span>
                </div>
                @if($subject->deleted_at)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Deleted At:</span>
                    <span class="text-sm text-red-600">{{ $subject->deleted_at->format('M d, Y h:i A') }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('admin.subjects.index') }}" class="text-gray-600 hover:text-gray-700 font-medium">
                ← Back to Subjects
            </a>
        </div>
    </div>
</div>

<!-- Class Schedules Section -->
<div class="p-6 pt-0">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Class Schedules ({{ $subject->classSchedules->count() }})</h3>
            </div>

            @if($subject->classSchedules->count() > 0)
                <div class="space-y-4 p-6">
                    @foreach($subject->classSchedules as $schedule)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Section</h4>
                                <p class="text-sm font-semibold text-gray-900">{{ $schedule->section->name }}</p>
                                <p class="text-xs text-gray-600">{{ $schedule->section->strand->code }} - Grade {{ $schedule->section->grade_level }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Teacher</h4>
                                <p class="text-sm font-semibold text-gray-900">{{ $schedule->teacher->full_name }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Academic Period</h4>
                                <p class="text-sm font-semibold text-gray-900">{{ $schedule->academicPeriod->name }}</p>
                                <p class="text-xs text-gray-600">{{ $schedule->academicPeriod->schoolYear->name }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Schedule & Room</h4>
                                @if($schedule->schedule_time)
                                    <p class="text-sm font-semibold text-gray-900">{{ $schedule->schedule_time }}</p>
                                @else
                                    <p class="text-sm text-gray-400">Not set</p>
                                @endif
                                @if($schedule->room)
                                    <p class="text-xs text-gray-600">Room: {{ $schedule->room }}</p>
                                @endif
                            </div>

                            <div class="md:col-span-2 flex justify-end">
                                <a href="{{ route('admin.class-schedules.show', $schedule) }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
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
</div>
@endsection
