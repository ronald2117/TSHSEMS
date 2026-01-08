@extends('layouts.app')

@section('title', 'My Classes')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Classes</h1>
            <p class="text-sm text-gray-600 mt-1">Manage your assigned classes for current semester</p>
        </div>
        <div class="text-sm text-gray-500">
            <span class="font-medium">{{ $classes->count() }}</span> class(es)
        </div>
    </div>

    <!-- Classes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($classes as $class)
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                <!-- Class Header -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 text-white">
                    <h3 class="font-bold text-lg mb-1">{{ $class->subject->name }}</h3>
                    <p class="text-green-50 text-sm">{{ $class->subject->code }}</p>
                </div>

                <!-- Class Details -->
                <div class="p-6 space-y-3">
                    <!-- Section -->
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $class->section->name }}</p>
                            <p class="text-xs text-gray-500">{{ $class->section->strand->name }}</p>
                        </div>
                    </div>

                    <!-- Schedule -->
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">
                            {{ ucfirst($class->day_of_week) }}
                        </span>
                    </div>

                    <!-- Time -->
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">
                            {{ date('g:i A', strtotime($class->start_time)) }} - {{ date('g:i A', strtotime($class->end_time)) }}
                        </span>
                    </div>

                    <!-- Room -->
                    @if($class->room)
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                            </svg>
                            <span class="text-sm text-gray-700">Room {{ $class->room }}</span>
                        </div>
                    @endif

                    <!-- Students Count -->
                    <div class="pt-3 border-t border-gray-100">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Students enrolled:</span>
                            <span class="font-semibold text-gray-900">{{ $class->studentEnrollments()->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="px-6 pb-6 flex gap-2">
                    <a href="{{ route('teacher.classes.show', $class) }}" class="flex-1 text-center px-4 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 font-medium text-sm transition">
                        View Details
                    </a>
                    <a href="{{ route('teacher.grading.show', $class) }}" class="flex-1 text-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium text-sm transition">
                        Grade
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-xl shadow-sm p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No classes assigned</h3>
                <p class="text-sm text-gray-500">You don't have any classes assigned for the current semester</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
