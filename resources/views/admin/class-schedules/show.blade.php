@extends('layouts.app')
@section('page_title', 'Class Schedule Details')
@section('page_subtitle', 'View schedule information and enrolled students.')

@section('toolbar')
    <div class="flex items-center justify-end gap-3 w-full">
        <a href="{{ route('admin.class-schedules.index') }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Schedules
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

    <!-- Schedule Details Card -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="px-8 py-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h2 class="text-gray-900 text-2xl font-bold">{{ $classSchedule->subject->code }}</h2>
                    <p class="text-gray-600 text-lg mt-1">{{ $classSchedule->subject->name }}</p>
                    <div class="flex items-center gap-4 mt-3">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                            @if(strtolower($classSchedule->subject->type) === 'core') bg-blue-100 text-blue-800
                            @elseif(strtolower($classSchedule->subject->type) === 'applied') bg-green-100 text-green-800
                            @elseif(strtolower($classSchedule->subject->type) === 'specialized') bg-purple-100 text-purple-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $classSchedule->subject->type }}
                        </span>
                        <span class="text-sm text-gray-600">
                            {{ $classSchedule->academicPeriod->name }} - {{ $classSchedule->academicPeriod->schoolYear->name }}
                        </span>
                    </div>
                </div>

                <!-- Action Icons -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.class-schedules.edit', $classSchedule) }}" 
                       class="p-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition shadow-sm" 
                       title="Edit Schedule">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>

                    <form method="POST" action="{{ route('admin.class-schedules.destroy', $classSchedule) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this class schedule?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition shadow-sm" 
                                title="Delete Schedule">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="px-8 py-6 bg-gray-50 border-y border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-600">Section</label>
                    <p class="text-gray-900 mt-1 font-semibold">{{ $classSchedule->section->name }}</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $classSchedule->section->strand->code }} - Grade {{ $classSchedule->section->grade_level }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Teacher</label>
                    <p class="text-gray-900 mt-1 font-semibold">{{ $classSchedule->teacher->full_name }}</p>
                    @if($classSchedule->teacher->email)
                        <p class="text-sm text-gray-600 mt-1">{{ $classSchedule->teacher->email }}</p>
                    @endif
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Schedule</label>
                    <p class="text-gray-900 mt-1">{{ $classSchedule->schedule_time ?? 'Not set' }}</p>
                    @if($classSchedule->room)
                        <p class="text-sm text-gray-600 mt-1">Room: {{ $classSchedule->room }}</p>
                    @endif
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Enrolled Students</label>
                    <p class="text-gray-900 mt-1 text-2xl font-bold">{{ $classSchedule->enrollments->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrolled Students -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Enrolled Students ({{ $classSchedule->enrollments->count() }})</h3>
        </div>

        @if($classSchedule->enrollments->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">#</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Student Name</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">LRN</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($classSchedule->enrollments as $enrollment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-green-600 flex items-center justify-center">
                                        <span class="text-white text-xs">{{ strtoupper(substr($enrollment->student->first_name, 0, 1)) }}{{ strtoupper(substr($enrollment->student->last_name, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $enrollment->student->full_name }}</p>
                                        <p class="text-sm text-gray-600">{{ $enrollment->student->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-mono text-gray-900">{{ $enrollment->student->studentProfile->lrn ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm text-gray-600 capitalize">{{ $enrollment->status }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12 text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <p>No students enrolled in this class yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection
