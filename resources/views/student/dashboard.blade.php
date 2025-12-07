@extends('layouts.app')

@section('page_title', 'Student Dashboard')
@section('page_subtitle', 'Welcome back! Here\'s your academic overview.')

@section('content')
<div class="p-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Grades</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalGrades }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Average Grade</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($averageGrade, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Current Section</p>
                    <p class="text-xl font-bold text-gray-900">{{ $profile->currentSection->name ?? 'N/A' }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Strand</p>
                    <p class="text-xl font-bold text-gray-900">{{ $profile->strand->name ?? 'N/A' }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Grades -->
    <div class="bg-white rounded-xl shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Recent Grades</h2>
        </div>
        <div class="p-6">
            @if($recentGrades->isEmpty())
                <p class="text-gray-600 text-center py-8">No grades available yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-gray-500 border-b border-gray-200">
                            <tr>
                                <th class="text-left py-3">Subject</th>
                                <th class="text-left py-3">Quarter</th>
                                <th class="text-center py-3">Grade</th>
                                <th class="text-center py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentGrades as $grade)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3">
                                    <p class="font-medium text-gray-900">{{ $grade->classSchedule->subject->name }}</p>
                                </td>
                                <td class="py-3">{{ $grade->quarter }}</td>
                                <td class="py-3 text-center">
                                    <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                                        {{ $grade->final_grade }}
                                    </span>
                                </td>
                                <td class="py-3 text-center">
                                    <span class="text-xs font-medium {{ $grade->remarks === 'Passed' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $grade->remarks }}
                                    </span>
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
@endsection
