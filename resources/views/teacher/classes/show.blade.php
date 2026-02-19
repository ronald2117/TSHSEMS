@extends('layouts.app')

@section('page_title', 'Class Details')
@section('page_subtitle', 'View class information, student list, and manage your class.')

@section('title', 'Class Details - ' . $classSchedule->subject->name)

@section('content')
<div class="p-6">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('teacher.classes.index') }}" class="inline-flex items-center text-primary-600 hover:text-primary-700">
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
            <a href="{{ route('teacher.classes.roster', $classSchedule) }}" target="_blank" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium transition">
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
                                        <div class="w-10 h-10 rounded-full bg-primary-600 flex items-center justify-center">
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
</div>
</div>
@endsection
