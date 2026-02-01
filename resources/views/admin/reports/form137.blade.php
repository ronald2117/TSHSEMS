@extends('layouts.app')
@section('page_title', 'Form 137 - Permanent Record')
@section('page_subtitle', $student->user->full_name ?? 'Student')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Print Button -->
        <div class="flex justify-end mb-4 print:hidden">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print Form 137
            </button>
        </div>

        <!-- Form 137 Document -->
        <div class="bg-white rounded-xl shadow-sm p-8 print:shadow-none print:p-4">
            <!-- Header -->
            <div class="text-center border-b-2 border-gray-900 pb-4 mb-6">
                <p class="text-sm">Republic of the Philippines</p>
                <p class="text-sm">Department of Education</p>
                <p class="text-sm font-semibold">TAYSAN SENIOR HIGH SCHOOL</p>
                <p class="text-xs text-gray-600">Taysan, Batangas</p>
                <h1 class="text-xl font-bold mt-4">SENIOR HIGH SCHOOL PERMANENT RECORD</h1>
                <p class="text-sm">(Form 137-SHS)</p>
            </div>

            <!-- Student Information -->
            <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                <div>
                    <p><span class="font-semibold">LRN:</span> {{ $student->lrn }}</p>
                    <p><span class="font-semibold">Name:</span> {{ $student->user->last_name ?? '' }}, {{ $student->user->first_name ?? '' }} {{ $student->user->middle_name ?? '' }}</p>
                    <p><span class="font-semibold">Sex:</span> {{ $student->gender ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Date of Birth:</span> {{ $student->birthdate ? \Carbon\Carbon::parse($student->birthdate)->format('F d, Y') : 'N/A' }}</p>
                </div>
                <div>
                    <p><span class="font-semibold">Track:</span> {{ $student->strand->track->name ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Strand:</span> {{ $student->strand->name ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Current Section:</span> {{ $student->currentSection->name ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Grade Level:</span> {{ $student->grade_level ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Academic Records by School Year -->
            @forelse($grades as $schoolYearId => $schoolYearGrades)
                @php
                    $schoolYear = \App\Models\SchoolYear::find($schoolYearId);
                    $gradesBySubject = $schoolYearGrades->groupBy('class_schedule_id');
                @endphp
                
                <div class="mb-8">
                    <h2 class="text-base font-bold bg-gray-100 p-2 mb-2">
                        School Year: {{ $schoolYear->name ?? 'N/A' }}
                    </h2>
                    
                    <table class="w-full text-sm border border-gray-300">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="border border-gray-300 px-2 py-1 text-left">Subject</th>
                                <th class="border border-gray-300 px-2 py-1 text-center w-16">Q1</th>
                                <th class="border border-gray-300 px-2 py-1 text-center w-16">Q2</th>
                                <th class="border border-gray-300 px-2 py-1 text-center w-16">Q3</th>
                                <th class="border border-gray-300 px-2 py-1 text-center w-16">Q4</th>
                                <th class="border border-gray-300 px-2 py-1 text-center w-16">Final</th>
                                <th class="border border-gray-300 px-2 py-1 text-center w-20">Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalFinal = 0; $subjectCount = 0; @endphp
                            @foreach($gradesBySubject as $scheduleId => $subjectGrades)
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
                                    <td class="border border-gray-300 px-2 py-1">{{ $subject->name ?? 'Subject' }}</td>
                                    <td class="border border-gray-300 px-2 py-1 text-center">{{ $q1 ? number_format($q1->final_grade, 0) : '-' }}</td>
                                    <td class="border border-gray-300 px-2 py-1 text-center">{{ $q2 ? number_format($q2->final_grade, 0) : '-' }}</td>
                                    <td class="border border-gray-300 px-2 py-1 text-center">{{ $q3 ? number_format($q3->final_grade, 0) : '-' }}</td>
                                    <td class="border border-gray-300 px-2 py-1 text-center">{{ $q4 ? number_format($q4->final_grade, 0) : '-' }}</td>
                                    <td class="border border-gray-300 px-2 py-1 text-center font-semibold">{{ $finalGrade ? number_format($finalGrade, 0) : '-' }}</td>
                                    <td class="border border-gray-300 px-2 py-1 text-center {{ $finalGrade && $finalGrade < 75 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $finalGrade ? ($finalGrade >= 75 ? 'PASSED' : 'FAILED') : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50 font-semibold">
                                <td class="border border-gray-300 px-2 py-1 text-right" colspan="5">General Weighted Average:</td>
                                <td class="border border-gray-300 px-2 py-1 text-center">
                                    {{ $subjectCount > 0 ? number_format($totalFinal / $subjectCount, 2) : '-' }}
                                </td>
                                <td class="border border-gray-300 px-2 py-1 text-center">
                                    @if($subjectCount > 0)
                                        @php $gwa = $totalFinal / $subjectCount; @endphp
                                        {{ $gwa >= 75 ? 'PASSED' : 'FAILED' }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <p>No approved grades found for this student.</p>
                </div>
            @endforelse

            <!-- Certification -->
            <div class="mt-12 pt-8 border-t border-gray-300">
                <p class="text-sm text-center mb-8">
                    I CERTIFY that this is a true record of <strong>{{ $student->user->full_name ?? 'the student' }}</strong> 
                    and that he/she is eligible for admission to Grade ______.
                </p>
                
                <div class="grid grid-cols-2 gap-8 mt-12">
                    <div class="text-center">
                        <div class="border-t border-gray-900 pt-2 mx-8">
                            <p class="font-semibold">Class Adviser</p>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="border-t border-gray-900 pt-2 mx-8">
                            <p class="font-semibold">School Principal</p>
                        </div>
                    </div>
                </div>
                
                <p class="text-xs text-center text-gray-500 mt-8">
                    Date Issued: {{ now()->format('F d, Y') }}
                </p>
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
