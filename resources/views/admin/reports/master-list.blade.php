@extends('layouts.app')
@section('page_title', 'Section Master List')
@section('page_subtitle', $section->name ?? 'Section')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Print Button -->
        <div class="flex justify-end mb-4 print:hidden">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print Master List
            </button>
        </div>

        <!-- Master List Document -->
        <div class="bg-white rounded-xl shadow-sm p-8 print:shadow-none print:p-4">
            <!-- Header -->
            <div class="text-center border-b-2 border-gray-900 pb-4 mb-6">
                <p class="text-sm">Republic of the Philippines</p>
                <p class="text-sm">Department of Education</p>
                <p class="text-sm font-semibold">TAYSAN SENIOR HIGH SCHOOL</p>
                <p class="text-xs text-gray-600">Taysan, Batangas</p>
                <h1 class="text-lg font-bold mt-4">SECTION MASTER LIST</h1>
            </div>

            <!-- Section Information -->
            <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                <div>
                    <p><span class="font-semibold">Section:</span> {{ $section->name }}</p>
                    <p><span class="font-semibold">Grade Level:</span> {{ $section->grade_level }}</p>
                    <p><span class="font-semibold">Strand:</span> {{ $section->strand->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p><span class="font-semibold">School Year:</span> {{ $section->schoolYear->name ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Adviser:</span> {{ $section->adviser->full_name ?? 'Not Assigned' }}</p>
                    <p><span class="font-semibold">Total Students:</span> {{ $section->studentProfiles->count() }}</p>
                </div>
            </div>

            <!-- Students Table -->
            <table class="w-full text-sm border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-3 py-2 text-center w-12">No.</th>
                        <th class="border border-gray-300 px-3 py-2 text-left">LRN</th>
                        <th class="border border-gray-300 px-3 py-2 text-left">Student Name</th>
                        <th class="border border-gray-300 px-3 py-2 text-center">Sex</th>
                        <th class="border border-gray-300 px-3 py-2 text-center">Birthdate</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $students = $section->studentProfiles->sortBy(function($student) {
                            return $student->user->last_name . $student->user->first_name;
                        });
                        $maleCount = 0;
                        $femaleCount = 0;
                    @endphp
                    
                    @forelse($students as $index => $student)
                        @php
                            if (strtolower($student->gender ?? '') === 'male') $maleCount++;
                            elseif (strtolower($student->gender ?? '') === 'female') $femaleCount++;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 px-3 py-2 text-center">{{ $index + 1 }}</td>
                            <td class="border border-gray-300 px-3 py-2">{{ $student->lrn }}</td>
                            <td class="border border-gray-300 px-3 py-2">
                                {{ $student->user->last_name ?? '' }}, {{ $student->user->first_name ?? '' }} {{ $student->user->middle_name ?? '' }}
                            </td>
                            <td class="border border-gray-300 px-3 py-2 text-center">
                                {{ $student->gender ? strtoupper(substr($student->gender, 0, 1)) : '-' }}
                            </td>
                            <td class="border border-gray-300 px-3 py-2 text-center">
                                {{ $student->birthdate ? \Carbon\Carbon::parse($student->birthdate)->format('m/d/Y') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="border border-gray-300 px-3 py-8 text-center text-gray-500">
                                No students enrolled in this section.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50">
                        <td class="border border-gray-300 px-3 py-2 font-semibold text-right" colspan="3">Total:</td>
                        <td class="border border-gray-300 px-3 py-2 text-center font-semibold" colspan="2">
                            {{ $students->count() }} students (M: {{ $maleCount }}, F: {{ $femaleCount }})
                        </td>
                    </tr>
                </tfoot>
            </table>

            <!-- Certification -->
            <div class="mt-12 pt-8 border-t border-gray-300">
                <p class="text-sm text-center mb-8">
                    This is to certify that the above list is the official enrollment of 
                    <strong>{{ $section->name }}</strong> for School Year <strong>{{ $section->schoolYear->name ?? 'N/A' }}</strong>.
                </p>
                
                <div class="grid grid-cols-2 gap-8 mt-12">
                    <div class="text-center">
                        <div class="border-t border-gray-900 pt-2 mx-8 mt-8">
                            <p class="font-semibold">{{ $section->adviser->full_name ?? '________________________' }}</p>
                            <p class="text-xs text-gray-600">Class Adviser</p>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="border-t border-gray-900 pt-2 mx-8 mt-8">
                            <p class="font-semibold">________________________</p>
                            <p class="text-xs text-gray-600">School Principal</p>
                        </div>
                    </div>
                </div>
                
                <p class="text-xs text-center text-gray-500 mt-8">
                    Date Generated: {{ now()->format('F d, Y') }}
                </p>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-6 print:hidden">
            <a href="{{ route('admin.sections.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Sections
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
