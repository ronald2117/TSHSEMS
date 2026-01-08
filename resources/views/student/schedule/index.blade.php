@extends('layouts.app')

@section('title', 'My Schedule')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Schedule</h1>
            <p class="text-sm text-gray-600 mt-1">Your class schedule for the current semester</p>
        </div>
        <button onclick="window.print()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium text-sm flex items-center gap-2">
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
            @if($scheduleByDay->isNotEmpty())
                @foreach($daysOfWeek as $day)
                    @if($scheduleByDay->has($day))
                        <div class="border-b border-gray-200 last:border-0">
                            <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                                <h3 class="font-semibold text-gray-900 capitalize">{{ $day }}</h3>
                            </div>
                            <div class="p-6 space-y-4">
                                @foreach($scheduleByDay[$day] as $schedule)
                                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                        <!-- Time -->
                                        <div class="flex-shrink-0 w-32">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ date('g:i A', strtotime($schedule->start_time)) }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ date('g:i A', strtotime($schedule->end_time)) }}
                                            </div>
                                        </div>

                                        <!-- Subject Details -->
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900">{{ $schedule->subject->name }}</h4>
                                            <p class="text-sm text-gray-600 mt-1">{{ $schedule->subject->code }}</p>
                                            <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{ $schedule->teacher->user->name }}
                                                </span>
                                                @if($schedule->room)
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                                        </svg>
                                                        Room {{ $schedule->room }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Subject Type Badge -->
                                        <div class="flex-shrink-0">
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                                {{ $schedule->subject->subject_type === 'core' ? 'bg-blue-100 text-blue-700' : 
                                                   ($schedule->subject->subject_type === 'applied' ? 'bg-purple-100 text-purple-700' : 'bg-green-100 text-green-700') }}">
                                                {{ ucfirst($schedule->subject->subject_type) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
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
