@extends('layouts.app')

@section('page_title', 'Attendance History')
@section('page_subtitle', $classSchedule->subject->name . ' - ' . $classSchedule->section->name)

@section('content')
<div class="p-6">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('teacher.attendance.index') }}" class="inline-flex items-center text-green-600 hover:text-green-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Attendance
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Records</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_records'] }}</p>
                </div>
                <div class="bg-gray-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Present</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['present'] }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Absent</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['absent'] }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Late</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['late'] }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Excused</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['excused'] }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter Records</h3>
        <form method="GET" action="{{ route('teacher.attendance.history', $classSchedule) }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" 
                       class="cursor-pointer w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>

            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" 
                       class="cursor-pointer w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>

            <div>
                <label for="student_id" class="block text-sm font-medium text-gray-700 mb-1">Filter by Student</label>
                <select id="student_id" name="student_id" 
                        class="cursor-pointer w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">All Students</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ $studentFilter == $student->id ? 'selected' : '' }}>
                            {{ $student->last_name ?? 'N/A' }}, {{ $student->first_name ?? $student->email }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="cursor-pointer flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition">
                    Apply Filters
                </button>
                <a href="{{ route('teacher.attendance.history', $classSchedule) }}" 
                   class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Tabs for Different Views -->
    <div class="bg-white rounded-xl shadow-sm mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button onclick="showTab('records')" id="tab-records" 
                        class="cursor-pointer tab-button active px-6 py-3 border-b-2 border-green-500 text-green-600 font-medium text-sm">
                    Attendance Records
                </button>
                <button onclick="showTab('summary')" id="tab-summary" 
                        class="cursor-pointer tab-button px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm">
                    Student Summary
                </button>
            </nav>
        </div>

        <!-- Records Tab Content -->
        <div id="content-records" class="tab-content p-6">
            @if($attendances->isEmpty())
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No attendance records found</h3>
                    <p class="text-sm text-gray-500">Try adjusting your filter criteria</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">LRN</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($attendances as $attendance)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $attendance->date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $attendance->student->last_name ?? 'N/A' }}, {{ $attendance->student->first_name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $attendance->student->studentProfile->lrn ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($attendance->status === 'Present')
                                            <span class="text-xs leading-5 font-semibold text-green-600">
                                                Present
                                            </span>
                                        @elseif($attendance->status === 'Absent')
                                            <span class="text-xs leading-5 font-semibold text-red-600">
                                                Absent
                                            </span>
                                        @elseif($attendance->status === 'Late')
                                            <span class="text-xs leading-5 font-semibold text-yellow-600">
                                                Late
                                            </span>
                                        @elseif($attendance->status === 'Excused')
                                            <span class="text-xs leading-5 font-semibold text-blue-600">
                                                Excused
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $attendance->remarks ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Summary Tab Content -->
        <div id="content-summary" class="tab-content hidden p-6">
            @if($students->isEmpty())
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No students enrolled</h3>
                    <p class="text-sm text-gray-500">This class doesn't have any enrolled students</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">LRN</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Days</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Present</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Absent</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Late</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Excused</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Attendance Rate</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($students as $student)
                                @php
                                    $stats = $studentStats[$student->id] ?? [
                                        'total' => 0,
                                        'present' => 0,
                                        'absent' => 0,
                                        'late' => 0,
                                        'excused' => 0,
                                        'attendance_rate' => 0,
                                    ];
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $student->last_name ?? 'N/A' }}, {{ $student->first_name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $student->studentProfile->lrn ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                        {{ $stats['total'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-2 py-1 text-xs font-semibold text-green-800">{{ $stats['present'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-2 py-1 text-xs font-semibold text-red-800">{{ $stats['absent'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-2 py-1 text-xs font-semibold text-yellow-800">{{ $stats['late'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-2 py-1 text-xs font-semibold text-blue-800">{{ $stats['excused'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center">
                                            <div class="w-full max-w-xs">
                                                <div class="flex items-center gap-2">
                                                    <div class="flex-1 bg-gray-200 rounded-full h-2 overflow-hidden">
                                                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $stats['attendance_rate'] }}%"></div>
                                                    </div>
                                                    <span class="text-sm font-medium text-gray-900">{{ $stats['attendance_rate'] }}%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-green-500', 'text-green-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById('tab-' + tabName);
    activeTab.classList.add('active', 'border-green-500', 'text-green-600');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
}
</script>
@endsection
