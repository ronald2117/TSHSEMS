@extends('layouts.app')

@section('page_title', 'Assessment Details')
@section('page_subtitle', 'View assessment details and student scores.')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <a href="{{ route('teacher.assessments.index') }}" class="text-green-600 hover:text-green-700 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Assessments
        </a>
    </div>

    <!-- Assessment Details Card -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $assessment->title }}</h1>
                <p class="text-sm text-gray-600 mt-1">
                    {{ $assessment->classSchedule->subject->name ?? 'Subject' }} - 
                    {{ $assessment->classSchedule->section->name ?? 'Section' }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 text-sm font-medium rounded-full {{ $assessment->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ $assessment->is_published ? 'Published' : 'Draft' }}
                </span>
                <a href="{{ route('teacher.assessments.edit', $assessment) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-500">Type</p>
                    <p class="mt-1 text-gray-900">
                        @switch($assessment->type)
                            @case('written_work')
                                Written Work
                                @break
                            @case('performance_task')
                                Performance Task
                                @break
                            @case('quarterly_assessment')
                                Quarterly Assessment
                                @break
                            @default
                                {{ $assessment->type }}
                        @endswitch
                    </p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Maximum Score</p>
                    <p class="mt-1 text-gray-900 font-semibold">{{ $assessment->max_score }} points</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Quarter</p>
                    <p class="mt-1 text-gray-900">Quarter {{ $assessment->quarter }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Assessment Date</p>
                    <p class="mt-1 text-gray-900">{{ $assessment->assessment_date ? $assessment->assessment_date->format('M d, Y') : 'Not set' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Scores -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Student Scores</h2>
                <p class="text-sm text-gray-600">
                    {{ $assessment->scores->count() }} of {{ $assessment->classSchedule->section->studentProfiles->count() ?? 0 }} students scored
                </p>
            </div>
            <a href="{{ route('teacher.grading.show', $assessment->classSchedule) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-green-600 bg-green-50 rounded-lg hover:bg-green-100 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Input Scores
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($assessment->scores as $index => $score)
                        @php
                            $percentage = $assessment->max_score > 0 ? ($score->score / $assessment->max_score) * 100 : 0;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $score->student->last_name ?? '' }}, {{ $score->student->first_name ?? '' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-sm font-semibold {{ $percentage >= 75 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $score->score }} / {{ $assessment->max_score }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-sm {{ $percentage >= 75 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($percentage, 1) }}%
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($percentage >= 90)
                                    <span class="text-xs font-medium text-green-600">Outstanding</span>
                                @elseif($percentage >= 85)
                                    <span class="text-xs font-medium text-blue-600">Very Satisfactory</span>
                                @elseif($percentage >= 80)
                                    <span class="text-xs font-medium text-yellow-600">Satisfactory</span>
                                @elseif($percentage >= 75)
                                    <span class="text-xs font-medium text-orange-600">Fairly Satisfactory</span>
                                @else
                                    <span class="text-xs font-medium text-red-600">Did Not Meet</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                No scores recorded yet. 
                                <a href="{{ route('teacher.grading.show', $assessment->classSchedule) }}" class="text-green-600 hover:text-green-700 font-medium">Start inputting scores</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($assessment->scores->count() > 0)
            <!-- Statistics -->
            <div class="p-6 border-t border-gray-200 bg-gray-50">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Score Statistics</h3>
                @php
                    $scores = $assessment->scores->pluck('score');
                    $avgScore = $scores->avg();
                    $avgPercentage = $assessment->max_score > 0 ? ($avgScore / $assessment->max_score) * 100 : 0;
                    $passedCount = $scores->filter(fn($s) => ($s / $assessment->max_score) * 100 >= 75)->count();
                @endphp
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div class="bg-white rounded-lg p-3 text-center">
                        <p class="text-xs text-gray-500">Highest</p>
                        <p class="text-lg font-bold text-green-600">{{ $scores->max() }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-3 text-center">
                        <p class="text-xs text-gray-500">Lowest</p>
                        <p class="text-lg font-bold text-red-600">{{ $scores->min() }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-3 text-center">
                        <p class="text-xs text-gray-500">Average</p>
                        <p class="text-lg font-bold text-blue-600">{{ number_format($avgScore, 1) }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-3 text-center">
                        <p class="text-xs text-gray-500">Average %</p>
                        <p class="text-lg font-bold text-purple-600">{{ number_format($avgPercentage, 1) }}%</p>
                    </div>
                    <div class="bg-white rounded-lg p-3 text-center">
                        <p class="text-xs text-gray-500">Passed (≥75%)</p>
                        <p class="text-lg font-bold text-gray-900">{{ $passedCount }} / {{ $scores->count() }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
