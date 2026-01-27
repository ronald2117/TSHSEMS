@extends('layouts.app')

@section('page_title', 'My Classes')
@section('page_subtitle', 'Manage your assigned classes for the current academic period.')

@section('content')
<div class="p-6">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Classes</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $classes->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.343 5.445 6 6.943 6 10c0 3.314 2.686 6 6 6s6-2.686 6-6c0-3.057-4.343-4.555-6-3.747z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Students</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $classes->sum(fn($c) => $c->enrollments->count()) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-between">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21.11a4 4 0 110-5.292m-6 5.292a4 4 0 110-5.292m4.125-4.355A3.015 3.015 0 0015 12m-9 0a3.015 3.015 0 001.875-.645M12 12v.01"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Active Period</p>
                    <p class="text-xl font-bold text-gray-900">
                        @if($classes->isNotEmpty())
                            {{ $classes->first()->academicPeriod->name ?? 'N/A' }}
                        @else
                            N/A
                        @endif
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">School Year</p>
                    <p class="text-xl font-bold text-gray-900">
                        @if($classes->isNotEmpty())
                            {{ $classes->first()->schoolYear->name ?? 'N/A' }}
                        @else
                            N/A
                        @endif
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Classes Grid -->
    <div class="bg-white rounded-xl shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">My Class Schedule</h2>
        </div>
        <div class="p-6">
            @if($classes->isEmpty())
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No classes assigned</h3>
                    <p class="text-sm text-gray-500">You don't have any classes assigned for the current academic period</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($classes as $class)
                        <div class="border border-gray-200 rounded-lg hover:shadow-md transition overflow-hidden">
                            <!-- Class Header -->
                            <div class="bg-green-50 px-4 py-3 border-b border-green-100">
                                <h3 class="font-semibold text-gray-900">{{ $class->subject->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $class->subject->code }}</p>
                            </div>

                            <!-- Class Details -->
                            <div class="p-4 space-y-3">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $class->section->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $class->section->strand->name }}</p>
                                    </div>
                                </div>

                                @if($class->schedule_time)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm text-gray-700">
                                            {{ $class->schedule_time }}
                                        </span>
                                    </div>
                                @endif

                                @if($class->room)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        <span class="text-sm text-gray-700">Room {{ $class->room }}</span>
                                    </div>
                                @endif

                                <div class="pt-3 border-t border-gray-100">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-500">Enrolled Students:</span>
                                        <span class="font-semibold text-gray-900">{{ $class->enrollments->count() }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="px-4 pb-4 flex gap-2">
                                <a href="{{ route('teacher.classes.show', $class) }}" class="flex-1 text-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium text-sm transition">
                                    View Class
                                </a>
                                <a href="{{ route('teacher.classes.roster', $class) }}" class="px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium text-sm transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
