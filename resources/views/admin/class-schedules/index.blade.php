@extends('layouts.app')
@section('page_title', 'Class Schedules')
@section('page_subtitle', 'Manage class schedules, teacher assignments, and subject enrollments.')

@section('toolbar')
    <div class="flex items-center justify-between gap-4 w-full">
        <div class="flex gap-3 flex-1">
            <!-- School Year Filter -->
            <select id="school_year_filter" class="px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">All School Years</option>
                @foreach($schoolYears as $sy)
                    <option value="{{ $sy->id }}" {{ request('school_year_id') == $sy->id ? 'selected' : '' }}>
                        {{ $sy->name }}
                    </option>
                @endforeach
            </select>

            <!-- Section Filter -->
            <select id="section_filter" class="px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">All Sections</option>
                @foreach($sections as $section)
                    <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                        {{ $section->name }} - {{ $section->strand->code }}
                    </option>
                @endforeach
            </select>

            <!-- Teacher Filter -->
            <select id="teacher_filter" class="px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">All Teachers</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                        {{ $teacher->full_name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <a href="{{ route('admin.class-schedules.create') }}" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition shadow-sm hover:shadow-md whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Schedule
        </a>
    </div>

    <script>
        // Filter functionality
        const schoolYearFilter = document.getElementById('school_year_filter');
        const sectionFilter = document.getElementById('section_filter');
        const teacherFilter = document.getElementById('teacher_filter');
        
        function applyFilters() {
            const url = new URL(window.location.href);
            url.searchParams.delete('page'); // Reset pagination
            
            const syValue = schoolYearFilter.value;
            const sectionValue = sectionFilter.value;
            const teacherValue = teacherFilter.value;
            
            if (syValue) url.searchParams.set('school_year_id', syValue);
            else url.searchParams.delete('school_year_id');
            
            if (sectionValue) url.searchParams.set('section_id', sectionValue);
            else url.searchParams.delete('section_id');
            
            if (teacherValue) url.searchParams.set('teacher_id', teacherValue);
            else url.searchParams.delete('teacher_id');
            
            window.location.href = url.toString();
        }
        
        schoolYearFilter.addEventListener('change', applyFilters);
        sectionFilter.addEventListener('change', applyFilters);
        teacherFilter.addEventListener('change', applyFilters);
    </script>
@endsection

@section('content')
<div class="p-6">
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Subject</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Section</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Teacher</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Period</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Schedule</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Enrolled</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($schedules as $schedule)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div>
                                <span class="text-sm font-semibold text-gray-900">{{ $schedule->subject->code }}</span>
                                <p class="text-xs text-gray-600 mt-1">{{ $schedule->subject->name }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <span class="text-sm text-gray-900">{{ $schedule->section->name }}</span>
                                <p class="text-xs text-gray-600 mt-1">{{ $schedule->section->strand->code }} - Grade {{ $schedule->section->grade_level }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-900">{{ $schedule->teacher->full_name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <span class="text-sm text-gray-900">{{ $schedule->academicPeriod->name }}</span>
                                <p class="text-xs text-gray-600 mt-1">{{ $schedule->academicPeriod->schoolYear->name }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-600">
                                @if($schedule->schedule_time)
                                    <p>{{ $schedule->schedule_time }}</p>
                                @else
                                    <span class="text-gray-400">Not set</span>
                                @endif
                                @if($schedule->room)
                                    <p class="text-xs text-gray-500 mt-1">Room: {{ $schedule->room }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-sm text-gray-600">{{ $schedule->enrolled_count }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-3">
                                <a href="{{ route('admin.class-schedules.show', $schedule) }}" 
                                   class="text-gray-600 hover:text-gray-700 transition mb-1" 
                                   title="View Details">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-lg font-medium">No class schedules found</p>
                                <p class="text-sm mt-1">Get started by creating a new class schedule.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($schedules->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $schedules->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
