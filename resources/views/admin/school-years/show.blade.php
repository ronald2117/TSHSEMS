@extends('layouts.app')

@section('page_title', 'School Year Details')
@section('page_subtitle', 'View and manage school year information')

@section('toolbar')
    <div class="flex items-center justify-end gap-3 w-full">
        <a href="{{ route('admin.school-years.index') }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to School Years
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

    <!-- School Year Profile Card -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Header Section -->
        <div class="px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div>
                        <h2 class="text-gray-900 text-2xl font-bold">{{ $schoolYear->name }}</h2>
                        <p class="text-gray-600 mt-1">{{ $schoolYear->year }}</p>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="text-white px-3 py-1 {{ $schoolYear->is_active ? 'bg-green-700' : 'bg-gray-500' }} rounded-full text-sm">
                                {{ $schoolYear->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Action Icons -->
                <div class="flex items-center gap-2">
                    @if(!$schoolYear->is_active)
                    <form method="POST" action="{{ route('admin.school-years.activate', $schoolYear) }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="p-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition shadow-sm" 
                                title="Activate School Year">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('admin.school-years.edit', $schoolYear) }}" 
                       class="p-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition shadow-sm" 
                       title="Edit School Year">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>

                    <form method="POST" action="{{ route('admin.school-years.destroy', $schoolYear) }}" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete this school year? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition shadow-sm" 
                                title="Delete School Year">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- School Year Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">School Year Information</h3>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-600">Academic Year</label>
                        <p class="text-gray-900 mt-1">{{ $schoolYear->name }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Start Date</label>
                        <p class="text-gray-900 mt-1">{{ $schoolYear->start_date->format('F d, Y') }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">End Date</label>
                        <p class="text-gray-900 mt-1">{{ $schoolYear->end_date->format('F d, Y') }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Duration</label>
                        <p class="text-gray-900 mt-1">{{ $schoolYear->start_date->diffInDays($schoolYear->end_date) }} days</p>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Statistics</h3>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Total Sections</label>
                        <p class="text-gray-900 mt-1">{{ count($schoolYear->sections) }} section{{ count($schoolYear->sections) != 1 ? 's' : '' }}</p>
                    </div>

                    @if(count($schoolYear->academicPeriods) > 0)
                    <div>
                        <label class="text-sm font-medium text-gray-600">Academic Periods</label>
                        <p class="text-gray-900 mt-1">{{ count($schoolYear->academicPeriods) }} period{{ count($schoolYear->academicPeriods) != 1 ? 's' : '' }}</p>
                    </div>
                    @endif

                    <div>
                        <label class="text-sm font-medium text-gray-600">Created</label>
                        <p class="text-gray-900 mt-1">
                            {{ $schoolYear->created_at->format('M d, Y') }}
                            <span class="text-sm text-gray-500">({{ $schoolYear->created_at->diffForHumans() }})</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sections -->
        @if($schoolYear->sections && count($schoolYear->sections) > 0)
        <div class="px-8 py-6 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Sections ({{ count($schoolYear->sections) }})</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($schoolYear->sections as $section)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-green-300 hover:shadow-sm transition">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $section->name }}</h4>
                                <p class="text-sm text-gray-600">Grade {{ $section->grade_level }}</p>
                            </div>
                            @if($section->strand)
                                <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded">{{ $section->strand->code }}</span>
                            @endif
                        </div>
                        @if($section->adviser)
                        <div class="flex items-center space-x-2 mb-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <p class="text-xs text-gray-600">{{ $section->adviser->full_name }}</p>
                        </div>
                        @endif
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <p class="text-xs text-gray-600">{{ $section->student_count }} student{{ $section->student_count != 1 ? 's' : '' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="px-8 py-6 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Sections</h3>
            <p class="text-gray-600">No sections created yet for this school year.</p>
        </div>
        @endif
    </div>
</div>
@endsection
