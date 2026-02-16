@extends('layouts.app')
@section('page_title', 'Grades Summary Report')
@section('page_subtitle', 'View grades by class and section')

@section('content')
<div class="p-6">
    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('admin.reports.grades') }}" class="flex flex-wrap gap-4 items-end">
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
            <div class="w-32">
                <label for="quarter" class="block text-sm font-medium text-gray-700 mb-2">Quarter</label>
                <select name="quarter" id="quarter" class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">All</option>
                    @for($i = 1; $i <= 4; $i++)
                        <option value="{{ $i }}" {{ request('quarter') == $i ? 'selected' : '' }}>Q{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium transition">
                    Generate Report
                </button>
                <a href="{{ route('admin.reports.grades') }}" class="px-4 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm font-medium transition">
                    Clear
                </a>
            </div>
        </form>
    </div>

    @if(request('section_id'))
        @php
            $section = $sections->find(request('section_id'));
            $students = $section ? $section->studentProfiles()->with('user')->get() : collect();
            $classSchedules = $section ? \App\Models\ClassSchedule::where('section_id', $section->id)->with('subject')->get() : collect();
            $quarterFilter = request('quarter');
        @endphp

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">
                        {{ $section->name ?? 'Section' }} - Grades Summary
                    </h2>
                    <p class="text-sm text-gray-600">
                        {{ $section->strand->name ?? '' }} | {{ $students->count() }} students
                        @if($quarterFilter) | Quarter {{ $quarterFilter }} @endif
                    </p>
                </div>
                <a href="{{ route('admin.reports.grades.export', request()->query()) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export to Excel
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50">#</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider sticky left-8 bg-gray-50 min-w-[150px]">Student Name</th>
                            @foreach($classSchedules as $schedule)
                                <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase tracking-wider min-w-[80px]">
                                    {{ $schedule->subject->code ?? 'SUB' }}
                                </th>
                            @endforeach
                            <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase tracking-wider min-w-[60px]">GWA</th>
                            <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase tracking-wider min-w-[80px]">Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($students as $index => $student)
                            @php
                                $totalGrade = 0;
                                $gradeCount = 0;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap text-gray-600 sticky left-0 bg-white">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-gray-900 font-medium sticky left-8 bg-white">
                                    {{ $student->user->last_name ?? '' }}, {{ substr($student->user->first_name ?? '', 0, 1) }}.
                                </td>
                                @foreach($classSchedules as $schedule)
                                    @php
                                        $gradeQuery = \App\Models\QuarterlyGrade::where('student_id', $student->user_id)
                                            ->where('class_schedule_id', $schedule->id)
                                            ->where('status', 'Approved');
                                        
                                        if ($quarterFilter) {
                                            $grade = $gradeQuery->where('quarter', $quarterFilter)->first();
                                        } else {
                                            $grade = $gradeQuery->orderBy('quarter', 'desc')->first();
                                        }
                                        
                                        if ($grade && $grade->final_grade) {
                                            $totalGrade += $grade->final_grade;
                                            $gradeCount++;
                                        }
                                    @endphp
                                    <td class="px-4 py-3 text-center text-gray-900">
                                        {{ $grade ? number_format($grade->final_grade, 0) : '-' }}
                                    </td>
                                @endforeach
                                @php
                                    $gwa = $gradeCount > 0 ? $totalGrade / $gradeCount : null;
                                @endphp
                                <td class="px-4 py-3 text-center font-semibold text-gray-900">
                                    {{ $gwa ? number_format($gwa, 2) : '-' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($gwa)
                                        <span class="text-xs font-semibold text-gray-900">
                                            {{ $gwa >= 75 ? 'Passed' : 'Failed' }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 4 + $classSchedules->count() }}" class="px-6 py-8 text-center text-gray-500">
                                    No students enrolled in this section.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
            @php
                $passedCount = 0;
                $failedCount = 0;
                $withHonors = 0;
                $withHighHonors = 0;
                
                foreach($students as $student) {
                    $studentGrades = \App\Models\QuarterlyGrade::where('student_id', $student->user_id)
                        ->where('status', 'Approved')
                        ->when($quarterFilter, fn($q) => $q->where('quarter', $quarterFilter))
                        ->pluck('final_grade');
                    
                    if ($studentGrades->count() > 0) {
                        $studentGwa = $studentGrades->avg();
                        if ($studentGwa >= 75) $passedCount++;
                        else $failedCount++;
                        if ($studentGwa >= 90) $withHonors++;
                        if ($studentGwa >= 95) $withHighHonors++;
                    }
                }
            @endphp
            
            <div class="bg-white rounded-xl shadow-sm p-4">
                <p class="text-sm text-gray-500">Total Students</p>
                <p class="text-2xl font-bold text-gray-900">{{ $students->count() }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4">
                <p class="text-sm text-gray-500">Passed</p>
                <p class="text-2xl font-bold text-gray-900">{{ $passedCount }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4">
                <p class="text-sm text-gray-500">Failed</p>
                <p class="text-2xl font-bold text-gray-900">{{ $failedCount }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4">
                <p class="text-sm text-gray-500">With Honors (90+)</p>
                <p class="text-2xl font-bold text-gray-900">{{ $withHonors }}</p>
            </div>
        </div>
    @else
        <!-- No Section Selected -->
        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Select a Section</h3>
            <p class="text-gray-500">Choose a section from the filter above to view grades summary.</p>
        </div>
    @endif
</div>
@endsection
