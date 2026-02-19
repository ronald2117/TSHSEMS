@extends('layouts.app')

@section('page_title', 'My Schedule')
@section('page_subtitle', 'View your class schedule and timetable.')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Schedule</h1>
            <p class="text-sm text-gray-600 mt-1">Your class schedule for the current semester</p>
        </div>
        <button onclick="window.print()" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-medium text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd"/>
            </svg>
            Print Schedule
        </button>
    </div>

    <!-- Student Info Card -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <p class="text-sm text-gray-500">Student Name</p>
                <p class="font-medium text-gray-900">{{ auth()->user()->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Student ID</p>
                <p class="font-medium text-gray-900">{{ $student->student_id }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Section</p>
                <p class="font-medium text-gray-900">{{ $student->section->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Strand</p>
                <p class="font-medium text-gray-900">{{ $student->section->strand->name ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <!-- Weekly Schedule -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            @if($schedules->isNotEmpty())
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Schedule</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($schedules as $schedule)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $schedule->subject->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $schedule->subject->code }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $schedule->teacher->full_name }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $schedule->schedule_time ?? 'TBA' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $schedule->room ?? 'TBA' }}</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No schedule available</h3>
                    <p class="text-sm text-gray-500">Your class schedule will appear here once it's been set up</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Legend -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-900 mb-3">Subject Types</h3>
        <div class="flex flex-wrap gap-4">
            <div class="flex items-center gap-2">
                <span class="w-4 h-4 bg-blue-100 rounded"></span>
                <span class="text-sm text-gray-600">Core Subject</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-4 h-4 bg-purple-100 rounded"></span>
                <span class="text-sm text-gray-600">Applied Subject</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-4 h-4 bg-green-100 rounded"></span>
                <span class="text-sm text-gray-600">Specialized Subject</span>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        body * { visibility: hidden; }
        .space-y-6, .space-y-6 * { visibility: visible; }
        button { display: none !important; }
        .no-print { display: none !important; }
    }
</style>
@endpush
@endsection
