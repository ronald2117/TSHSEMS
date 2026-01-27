@extends('layouts.app')

@section('page_title', 'Enter Scores - ' . $classSchedule->subject->name)
@section('page_subtitle', $classSchedule->section->name . ' • Enter assessment scores')

@section('content')
<div class="p-6">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('teacher.grading.show', $classSchedule) }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Grades
        </a>
    </div>

    <!-- Class Info Card -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900">{{ $classSchedule->subject->name }}</h2>
        <p class="text-sm text-gray-600 mt-1">{{ $classSchedule->section->name }} • {{ $students->count() }} Students</p>
    </div>

    <!-- Instructions -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
            <div>
                <h3 class="font-semibold text-blue-900 text-sm">Instructions</h3>
                <p class="text-sm text-blue-800 mt-1">Enter scores for each assessment. Leave blank if not yet graded. Changes are saved automatically.</p>
            </div>
        </div>
    </div>

    <form action="{{ route('teacher.grading.update', $classSchedule) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Quarter Tabs -->
        <div class="bg-white rounded-xl shadow-sm mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    @for($q = 1; $q <= 4; $q++)
                        <button type="button" onclick="selectQuarter({{ $q }})" 
                                class="quarter-tab px-6 py-3 text-sm font-medium border-b-2 transition {{ $q == 1 ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                                data-quarter="{{ $q }}">
                            Quarter {{ $q }}
                        </button>
                    @endfor
                </nav>
            </div>
        </div>

        <!-- Score Entry Tables by Quarter -->
        @for($quarter = 1; $quarter <= 4; $quarter++)
            <div class="quarter-content {{ $quarter == 1 ? '' : 'hidden' }}" data-quarter="{{ $quarter }}">
                @php
                    $quarterAssessments = collect($assessments)->map(function($group) use ($quarter) {
                        return $group->filter(fn($a) => $a->quarter == $quarter);
                    })->filter(fn($group) => $group->isNotEmpty());
                @endphp

                @if($quarterAssessments->isEmpty())
                    <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">No assessments for Quarter {{ $quarter }}</h3>
                        <p class="text-sm text-gray-500">Create assessments first before entering scores.</p>
                        <a href="{{ route('teacher.assessments.create', ['class_schedule_id' => $classSchedule->id]) }}" class="mt-4 inline-block px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium text-sm">
                            Create Assessment
                        </a>
                    </div>
                @else
                    @foreach(['written_work' => 'Written Work', 'performance_task' => 'Performance Task', 'quarterly_assessment' => 'Quarterly Exam'] as $type => $label)
                        @if($quarterAssessments->has($type) && $quarterAssessments[$type]->isNotEmpty())
                            <div class="bg-white rounded-xl shadow-sm mb-6">
                                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                                    <h3 class="font-semibold text-gray-900">{{ $label }}</h3>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase sticky left-0 bg-gray-50">Student</th>
                                                @foreach($quarterAssessments[$type] as $assessment)
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase whitespace-nowrap">
                                                        {{ $assessment->title }}
                                                        <br>
                                                        <span class="text-xs text-gray-400">({{ $assessment->max_score }} pts)</span>
                                                    </th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($students as $student)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap sticky left-0 bg-white">
                                                        <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                                        <div class="text-xs text-gray-500">{{ $student->studentProfile?->lrn ?? 'N/A' }}</div>
                                                    </td>
                                                    @foreach($quarterAssessments[$type] as $assessment)
                                                        @php
                                                            $score = $scores->get($student->id)?->firstWhere('assessment_id', $assessment->id);
                                                        @endphp
                                                        <td class="px-6 py-4 text-center">
                                                            <input type="number" 
                                                                   name="scores[{{ $student->id }}][{{ $assessment->id }}]" 
                                                                   value="{{ $score->score ?? '' }}"
                                                                   min="0" 
                                                                   max="{{ $assessment->max_score }}" 
                                                                   step="0.01"
                                                                   class="w-20 px-2 py-1 border border-gray-300 rounded focus:ring-green-500 focus:border-green-500 text-center"
                                                                   placeholder="—">
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
        @endfor

        <!-- Save Button -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('teacher.grading.show', $classSchedule) }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition">
                Save Scores
            </button>
        </div>
    </form>
</div>

<script>
function selectQuarter(quarter) {
    // Update tabs
    document.querySelectorAll('.quarter-tab').forEach(tab => {
        if (parseInt(tab.dataset.quarter) === quarter) {
            tab.classList.remove('border-transparent', 'text-gray-500');
            tab.classList.add('border-green-600', 'text-green-600');
        } else {
            tab.classList.remove('border-green-600', 'text-green-600');
            tab.classList.add('border-transparent', 'text-gray-500');
        }
    });
    
    // Update content
    document.querySelectorAll('.quarter-content').forEach(content => {
        if (parseInt(content.dataset.quarter) === quarter) {
            content.classList.remove('hidden');
        } else {
            content.classList.add('hidden');
        }
    });
}
</script>
@endsection
