@extends('layouts.app')
@section('page_title', 'Attendance Summary Report')
@section('page_subtitle', 'View attendance records by section')

@section('content')
<div class="p-6">
    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('admin.reports.attendance') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label for="section_id" class="block text-sm font-medium text-gray-700 mb-2">Section</label>
                <select name="section_id" id="section_id" class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Select Section</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                            Grade {{ $section->grade_level ?? '' }} - {{ $section->name }} ({{ $section->strand->name ?? 'No Strand' }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-40">
                <label for="month" class="block text-sm font-medium text-gray-700 mb-2">Month</label>
                <input type="month" name="month" id="month" value="{{ request('month', date('Y-m')) }}"
                       class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium transition">
                    Generate Report
                </button>
                <a href="{{ route('admin.reports.attendance') }}" class="px-4 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm font-medium transition">
                    Clear
                </a>
            </div>
        </form>
    </div>

    @if(request('section_id'))
        @php
            $section = $sections->find(request('section_id'));
            $students = $section ? $section->studentProfiles()->with('user')->get() : collect();
            $month = request('month', date('Y-m'));
            $monthStart = \Carbon\Carbon::parse($month)->startOfMonth();
            $monthEnd = \Carbon\Carbon::parse($month)->endOfMonth();
            $daysInMonth = $monthStart->daysInMonth;
            
            // Get all attendance records for this section in the given month
            $attendanceRecords = \App\Models\Attendance::whereHas('classSchedule', function($q) use ($section) {
                $q->where('section_id', $section->id);
            })
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->get()
            ->groupBy('student_id');
        @endphp

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">
                        {{ $section->name ?? 'Section' }} - Attendance Summary
                    </h2>
                    <p class="text-sm text-gray-600">
                        {{ $monthStart->format('F Y') }} | {{ $students->count() }} students
                    </p>
                </div>
                <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Print
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50">#</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider sticky left-8 bg-gray-50 min-w-[150px]">Student Name</th>
                            <th class="px-3 py-3 text-center font-medium text-gray-500 uppercase bg-green-50">P</th>
                            <th class="px-3 py-3 text-center font-medium text-gray-500 uppercase bg-yellow-50">L</th>
                            <th class="px-3 py-3 text-center font-medium text-gray-500 uppercase bg-red-50">A</th>
                            <th class="px-3 py-3 text-center font-medium text-gray-500 uppercase bg-blue-50">E</th>
                            <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">Total Days</th>
                            <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">% Present</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($students as $index => $student)
                            @php
                                $studentAttendance = $attendanceRecords->get($student->user_id, collect());
                                $present = $studentAttendance->where('status', 'Present')->count();
                                $late = $studentAttendance->where('status', 'Late')->count();
                                $absent = $studentAttendance->where('status', 'Absent')->count();
                                $excused = $studentAttendance->where('status', 'Excused')->count();
                                $totalRecorded = $present + $late + $absent + $excused;
                                $percentPresent = $totalRecorded > 0 ? (($present + $late) / $totalRecorded) * 100 : 0;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap text-gray-600 sticky left-0 bg-white">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-gray-900 font-medium sticky left-8 bg-white">
                                    {{ $student->user->last_name ?? '' }}, {{ $student->user->first_name ?? '' }}
                                </td>
                                <td class="px-3 py-3 text-center text-green-600 font-semibold bg-green-50/50">{{ $present }}</td>
                                <td class="px-3 py-3 text-center text-yellow-600 font-semibold bg-yellow-50/50">{{ $late }}</td>
                                <td class="px-3 py-3 text-center text-red-600 font-semibold bg-red-50/50">{{ $absent }}</td>
                                <td class="px-3 py-3 text-center text-blue-600 font-semibold bg-blue-50/50">{{ $excused }}</td>
                                <td class="px-4 py-3 text-center text-gray-900">{{ $totalRecorded }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-xs font-semibold {{ $percentPresent >= 80 ? 'text-green-600' : ($percentPresent >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ number_format($percentPresent, 1) }}%
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                    No students enrolled in this section.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Legend -->
        <div class="mt-6 bg-white rounded-xl shadow-sm p-4">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Legend</h3>
            <div class="flex flex-wrap gap-4 text-sm">
                <span class="flex items-center gap-2">
                    <span class="w-4 h-4 bg-green-100 rounded"></span>
                    P = Present
                </span>
                <span class="flex items-center gap-2">
                    <span class="w-4 h-4 bg-yellow-100 rounded"></span>
                    L = Late
                </span>
                <span class="flex items-center gap-2">
                    <span class="w-4 h-4 bg-red-100 rounded"></span>
                    A = Absent
                </span>
                <span class="flex items-center gap-2">
                    <span class="w-4 h-4 bg-blue-100 rounded"></span>
                    E = Excused
                </span>
            </div>
        </div>
    @else
        <!-- No Section Selected -->
        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Select a Section</h3>
            <p class="text-gray-500">Choose a section from the filter above to view attendance summary.</p>
        </div>
    @endif
</div>
@endsection
