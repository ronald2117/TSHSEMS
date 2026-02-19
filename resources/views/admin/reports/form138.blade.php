@extends('layouts.app')
@section('page_title', 'Form 138 - Report Card')
@section('page_subtitle', $student->user->full_name ?? 'Student')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Print Button & Filters -->
        <div class="flex justify-between items-center mb-4 print:hidden">
            <form method="GET" action="{{ route('admin.reports.form138', $student->id) }}" class="flex gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quarter</label>
                    <select name="quarter" class="px-3 py-2 text-sm border border-gray-300 rounded-lg">
                        <option value="">All Quarters</option>
                        @for($i = 1; $i <= 4; $i++)
                            <option value="{{ $i }}" {{ request('quarter') == $i ? 'selected' : '' }}>Quarter {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Filter
                </button>
            </form>
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print Report Card
            </button>
        </div>

        <!-- Form 138 Document -->
        <div class="bg-white rounded-xl shadow-sm p-8 print:shadow-none print:p-4">
            <!-- Header -->
            <div class="text-center border-b-2 border-gray-900 pb-4 mb-6">
                <div class="flex items-center justify-center gap-4 mb-2">
                    <img src="{{ asset('images/deped-logo.png') }}" alt="DepEd Logo" class="h-16 w-16 object-contain" onerror="this.style.display='none'">
                    <div>
                        <p class="text-sm">Republic of the Philippines</p>
                        <p class="text-sm">Department of Education</p>
                        <p class="text-sm font-semibold">TAYSAN SENIOR HIGH SCHOOL</p>
                        <p class="text-xs text-gray-600">Taysan, Batangas</p>
                    </div>
                    <img src="{{ asset('images/school-logo.png') }}" alt="School Logo" class="h-16 w-16 object-contain" onerror="this.style.display='none'">
                </div>
                <h1 class="text-lg font-bold mt-4">REPORT CARD</h1>
                <p class="text-sm">(Form 138-SHS)</p>
            </div>

            <!-- Student Information -->
            <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                <div>
                    <p><span class="font-semibold">LRN:</span> {{ $student->lrn }}</p>
                    <p><span class="font-semibold">Name:</span> {{ $student->user->last_name ?? '' }}, {{ $student->user->first_name ?? '' }} {{ $student->user->middle_name ?? '' }}</p>
                    <p><span class="font-semibold">Grade & Section:</span> Grade {{ $student->grade_level ?? '' }} - {{ $student->currentSection->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p><span class="font-semibold">Track:</span> {{ $student->strand->track->name ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Strand:</span> {{ $student->strand->name ?? 'N/A' }}</p>
                    <p><span class="font-semibold">School Year:</span> {{ $student->currentSection->schoolYear->name ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Grades Table -->
            @php
                $gradesBySubject = $grades->groupBy('class_schedule_id');
            @endphp
            
            <table class="w-full text-sm border border-gray-300 mb-6">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-3 py-2 text-left" rowspan="2">Learning Areas</th>
                        <th class="border border-gray-300 px-2 py-1 text-center" colspan="4">Quarterly Grades</th>
                        <th class="border border-gray-300 px-2 py-1 text-center" rowspan="2">Final<br>Grade</th>
                        <th class="border border-gray-300 px-2 py-1 text-center" rowspan="2">Remarks</th>
                    </tr>
                    <tr class="bg-gray-50">
                        <th class="border border-gray-300 px-2 py-1 text-center w-12">1</th>
                        <th class="border border-gray-300 px-2 py-1 text-center w-12">2</th>
                        <th class="border border-gray-300 px-2 py-1 text-center w-12">3</th>
                        <th class="border border-gray-300 px-2 py-1 text-center w-12">4</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalFinal = 0; $subjectCount = 0; @endphp
                    
                    @forelse($gradesBySubject as $scheduleId => $subjectGrades)
                        @php
                            $subject = $subjectGrades->first()->classSchedule->subject ?? null;
                            $q1 = $subjectGrades->where('quarter', 1)->first();
                            $q2 = $subjectGrades->where('quarter', 2)->first();
                            $q3 = $subjectGrades->where('quarter', 3)->first();
                            $q4 = $subjectGrades->where('quarter', 4)->first();
                            
                            $quarters = collect([$q1, $q2, $q3, $q4])->filter()->pluck('final_grade');
                            $finalGrade = $quarters->count() > 0 ? $quarters->avg() : null;
                            
                            if ($finalGrade) {
                                $totalFinal += $finalGrade;
                                $subjectCount++;
                            }
                        @endphp
                        <tr>
                            <td class="border border-gray-300 px-3 py-2">{{ $subject->name ?? 'Subject' }}</td>
                            <td class="border border-gray-300 px-2 py-2 text-center {{ $q1 && $q1->final_grade < 75 ? 'text-red-600' : '' }}">
                                {{ $q1 ? number_format($q1->final_grade, 0) : '' }}
                            </td>
                            <td class="border border-gray-300 px-2 py-2 text-center {{ $q2 && $q2->final_grade < 75 ? 'text-red-600' : '' }}">
                                {{ $q2 ? number_format($q2->final_grade, 0) : '' }}
                            </td>
                            <td class="border border-gray-300 px-2 py-2 text-center {{ $q3 && $q3->final_grade < 75 ? 'text-red-600' : '' }}">
                                {{ $q3 ? number_format($q3->final_grade, 0) : '' }}
                            </td>
                            <td class="border border-gray-300 px-2 py-2 text-center {{ $q4 && $q4->final_grade < 75 ? 'text-red-600' : '' }}">
                                {{ $q4 ? number_format($q4->final_grade, 0) : '' }}
                            </td>
                            <td class="border border-gray-300 px-2 py-2 text-center font-semibold {{ $finalGrade && $finalGrade < 75 ? 'text-red-600' : '' }}">
                                {{ $finalGrade ? number_format($finalGrade, 0) : '' }}
                            </td>
                            <td class="border border-gray-300 px-2 py-2 text-center text-xs">
                                @if($finalGrade)
                                    <span class="{{ $finalGrade >= 75 ? 'text-primary-600' : 'text-red-600' }}">
                                        {{ $finalGrade >= 75 ? 'PASSED' : 'FAILED' }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="border border-gray-300 px-3 py-8 text-center text-gray-500">
                                No approved grades found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($subjectCount > 0)
                <tfoot>
                    <tr class="bg-gray-50">
                        <td class="border border-gray-300 px-3 py-2 font-semibold text-right" colspan="5">
                            General Weighted Average
                        </td>
                        <td class="border border-gray-300 px-2 py-2 text-center font-bold">
                            {{ number_format($totalFinal / $subjectCount, 2) }}
                        </td>
                        <td class="border border-gray-300 px-2 py-2 text-center text-xs">
                            @php $gwa = $totalFinal / $subjectCount; @endphp
                            <span class="{{ $gwa >= 75 ? 'text-primary-600' : 'text-red-600' }} font-semibold">
                                {{ $gwa >= 75 ? 'PASSED' : 'FAILED' }}
                            </span>
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>

            <!-- Descriptors -->
            <div class="grid grid-cols-2 gap-8 text-xs mb-8">
                <div>
                    <p class="font-semibold mb-2">Descriptors</p>
                    <table class="w-full border border-gray-300">
                        <tr><td class="border border-gray-300 px-2 py-1">Outstanding</td><td class="border border-gray-300 px-2 py-1 text-center">90-100</td></tr>
                        <tr><td class="border border-gray-300 px-2 py-1">Very Satisfactory</td><td class="border border-gray-300 px-2 py-1 text-center">85-89</td></tr>
                        <tr><td class="border border-gray-300 px-2 py-1">Satisfactory</td><td class="border border-gray-300 px-2 py-1 text-center">80-84</td></tr>
                        <tr><td class="border border-gray-300 px-2 py-1">Fairly Satisfactory</td><td class="border border-gray-300 px-2 py-1 text-center">75-79</td></tr>
                        <tr><td class="border border-gray-300 px-2 py-1">Did Not Meet Expectations</td><td class="border border-gray-300 px-2 py-1 text-center">Below 75</td></tr>
                    </table>
                </div>
                <div>
                    <p class="font-semibold mb-2">Parent/Guardian's Signature</p>
                    <table class="w-full border border-gray-300">
                        <tr><td class="border border-gray-300 px-2 py-3">1st Quarter:</td></tr>
                        <tr><td class="border border-gray-300 px-2 py-3">2nd Quarter:</td></tr>
                        <tr><td class="border border-gray-300 px-2 py-3">3rd Quarter:</td></tr>
                        <tr><td class="border border-gray-300 px-2 py-3">4th Quarter:</td></tr>
                    </table>
                </div>
            </div>

            <!-- Certification -->
            <div class="mt-8 pt-4 border-t border-gray-300">
                <div class="grid grid-cols-2 gap-8 mt-8">
                    <div class="text-center">
                        <div class="border-t border-gray-900 pt-2 mx-8 mt-12">
                            <p class="font-semibold text-sm">Class Adviser</p>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="border-t border-gray-900 pt-2 mx-8 mt-12">
                            <p class="font-semibold text-sm">School Principal</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-6 print:hidden">
            <a href="{{ route('admin.reports.students') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Student List
            </a>
        </div>
    </div>
</div>

<style>
@media print {
    body { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    .print\:hidden { display: none !important; }
    .print\:shadow-none { box-shadow: none !important; }
    .print\:p-4 { padding: 1rem !important; }
}
</style>
@endsection
