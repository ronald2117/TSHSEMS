@extends('layouts.app')

@section('title', 'Class Details - ' . $classSchedule->subject->name)

@section('content')
<div class="space-y-6">
    <!-- Page Header with Back Button -->
    <div class="flex items-center gap-4">
        <a href="{{ route('teacher.classes.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1">
            <h1 class="text-2xl font-bold text-gray-900">{{ $classSchedule->subject->name }}</h1>
            <p class="text-sm text-gray-600 mt-1">{{ $classSchedule->subject->code }} • {{ $classSchedule->section->name }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('teacher.classes.roster', $classSchedule) }}" target="_blank" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd"/>
                </svg>
                Print Roster
            </a>
        </div>
    </div>

    <!-- Class Information Card -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Class Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-gray-500 mb-1">Section</p>
                <p class="font-medium text-gray-900">{{ $classSchedule->section->name }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $classSchedule->section->strand->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Schedule</p>
                <p class="font-medium text-gray-900">{{ ucfirst($classSchedule->day_of_week) }}</p>
                <p class="text-xs text-gray-500 mt-0.5">
                    {{ date('g:i A', strtotime($classSchedule->start_time)) }} - {{ date('g:i A', strtotime($classSchedule->end_time)) }}
                </p>
            </div>
            @if($classSchedule->room)
                <div>
                    <p class="text-sm text-gray-500 mb-1">Room</p>
                    <p class="font-medium text-gray-900">{{ $classSchedule->room }}</p>
                </div>
            @endif
            <div>
                <p class="text-sm text-gray-500 mb-1">Subject Type</p>
                <p class="font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $classSchedule->subject->subject_type) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Academic Period</p>
                <p class="font-medium text-gray-900">{{ $classSchedule->academicPeriod->name }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $classSchedule->schoolYear->year_start }}-{{ $classSchedule->schoolYear->year_end }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Total Students</p>
                <p class="font-medium text-gray-900 text-2xl">{{ $students->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Students List -->
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Enrolled Students</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Student ID
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Section
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($students as $enrollment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $enrollment->student->student_id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                        <span class="text-green-700 font-semibold text-sm">
                                            {{ substr($enrollment->student->user->name, 0, 2) }}
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $enrollment->student->user->name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $enrollment->student->section->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $enrollment->student->user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                                No students enrolled in this class yet
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('teacher.grading.show', $classSchedule) }}" class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition flex items-center gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900">Grade Students</h3>
                <p class="text-xs text-gray-500">Input and manage grades</p>
            </div>
        </a>
        
        <a href="{{ route('teacher.attendance.create', ['class_schedule' => $classSchedule->id]) }}" class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition flex items-center gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900">Mark Attendance</h3>
                <p class="text-xs text-gray-500">Record student attendance</p>
            </div>
        </a>
        
        <a href="{{ route('teacher.classes.roster', $classSchedule) }}" target="_blank" class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition flex items-center gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900">Print Roster</h3>
                <p class="text-xs text-gray-500">Generate class roster</p>
            </div>
        </a>
    </div>
</div>
@endsection
