@extends('layouts.app')

@section('title', 'Class Details - ' . $classSchedule->subject->name)

@section('content')
<div class="p-6">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('teacher.classes.index') }}" class="inline-flex items-center text-green-600 hover:text-green-700">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Classes
        </a>
    </div>

    <!-- Class Info Card -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">{{ $classSchedule->subject->name }}</h2>
                <p class="text-sm text-gray-600 mt-1">{{ $classSchedule->section->name }} • {{ $students->count() }} Students</p>
            </div>
            <a href="{{ route('teacher.classes.roster', $classSchedule) }}" target="_blank" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition">
                Print Roster
            </a>
        </div>
    </div>

<div class="space-y-6">

    <!-- Class Information Card -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Class Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-gray-500 mb-1">Section</p>
                <p class="font-medium text-gray-900">{{ $classSchedule->section->name }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $classSchedule->section->strand->name }}</p>
            </div>
            @if($classSchedule->schedule_time)
            <div>
                <p class="text-sm text-gray-500 mb-1">Schedule</p>
                <p class="font-medium text-gray-900">{{ $classSchedule->schedule_time }}</p>
            </div>
            @endif
            @if($classSchedule->room)
                <div>
                    <p class="text-sm text-gray-500 mb-1">Room</p>
                    <p class="font-medium text-gray-900">{{ $classSchedule->room }}</p>
                </div>
            @endif
            <div>
                <p class="text-sm text-gray-500 mb-1">Subject Type</p>
                <p class="font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $classSchedule->subject->type) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Academic Period</p>
                <p class="font-medium text-gray-900">{{ $classSchedule->academicPeriod->name }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $classSchedule->academicPeriod->schoolYear->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Total Students</p>
                <p class="font-medium text-gray-900 text-2xl">{{ $students->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Students List -->
    <div class="bg-white rounded-xl shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Enrolled Students</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">
                            Student ID
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">
                            Name
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">
                            Section
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">
                            Email
                        </th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($students as $enrollment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-600">{{ $enrollment->student->studentProfile->lrn ?? 'N/A' }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    @if($enrollment->student->avatar_path && file_exists(public_path('storage/' . $enrollment->student->avatar_path)))
                                        <img src="{{ asset('storage/' . $enrollment->student->avatar_path) }}" alt="{{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}" class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center">
                                            <span class="text-white text-sm">{{ strtoupper(substr($enrollment->student->first_name, 0, 1)) }}{{ strtoupper(substr($enrollment->student->last_name, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $enrollment->student->studentProfile->currentSection->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $enrollment->student->email }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm text-gray-600">
                                    Active
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-600">
                                No students enrolled in this class yet.
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
</div>
@endsection
