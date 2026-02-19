@extends('layouts.app')

@section('page_title', 'Attendance')
@section('page_subtitle', 'Record and manage student attendance.')

@section('content')
<div class="p-6">
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Classes List -->
    <div class="bg-white rounded-xl shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">My Classes</h2>
        </div>
        <div class="p-6">
            @if($classSchedules->isEmpty())
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No classes assigned</h3>
                    <p class="text-sm text-gray-500">You don't have any classes assigned for attendance recording</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($classSchedules as $schedule)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="mb-4">
                                <h3 class="font-semibold text-gray-900">{{ $schedule->subject->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $schedule->section->name }}</p>
                            </div>

                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21.11a4 4 0 110-5.292m-6 5.292a4 4 0 110-5.292m4.125-4.355A3.015 3.015 0 0015 12m-9 0a3.015 3.015 0 001.875-.645M12 12v.01"></path>
                                    </svg>
                                    <span>{{ $schedule->enrollments->count() }} Students</span>
                                </div>
                                @if($schedule->schedule_time)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>{{ $schedule->schedule_time }}</span>
                                    </div>
                                @endif
                                @if($schedule->room)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        <span>Room {{ $schedule->room }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="flex gap-2">
                                <a href="{{ route('teacher.attendance.roster', $schedule) }}" class="flex-1 text-center bg-primary-600 hover:bg-primary-700 text-white py-2 rounded-lg font-medium transition text-sm">
                                    Take Attendance
                                </a>
                                <a href="{{ route('teacher.attendance.history', $schedule) }}" class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-medium transition text-sm">
                                    View History
                                </a>
                            </div>
                            <a href="{{ route('teacher.attendance.monthly-summary', $schedule) }}" class="mt-2 text-center bg-gray-600 hover:bg-gray-700 text-white py-2 rounded-lg font-medium transition text-sm flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Monthly Summary
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
