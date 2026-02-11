@extends('layouts.app')

@section('page_title', 'Grading - ' . $classSchedule->subject->name)
@section('page_subtitle', $classSchedule->section->name . ' • ' . $classSchedule->academicPeriod->name)

@section('content')
<div class="p-6">
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('teacher.grading.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Grading
        </a>
    </div>

    <!-- Class Info Card -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">{{ $classSchedule->subject->name }}</h2>
                <p class="text-sm text-gray-600 mt-1">{{ $classSchedule->section->name }} • {{ $classSchedule->enrollments->count() }} Students</p>
            </div>
            <a href="{{ route('teacher.grading.edit', $classSchedule) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition">
                Enter Scores
            </a>
        </div>
    </div>

    <!-- Quarter Tabs -->
    <div class="bg-white rounded-xl shadow-sm mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                @for($q = 1; $q <= 4; $q++)
                    <button onclick="selectQuarter({{ $q }})" 
                            class="cursor-pointer quarter-tab px-6 py-3 text-sm font-medium border-b-2 transition {{ $q == 1 ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                            data-quarter="{{ $q }}">
                        Quarter {{ $q }}
                    </button>
                @endfor
            </nav>
        </div>
    </div>

    <!-- Grades Table -->
    @for($quarter = 1; $quarter <= 4; $quarter++)
        <div class="quarter-content {{ $quarter == 1 ? '' : 'hidden' }}" data-quarter="{{ $quarter }}">
            <div class="bg-white rounded-xl shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Quarter {{ $quarter }} Grades</h3>
                    <p class="text-sm text-gray-500 mt-1">Submit grades individually when ready for approval</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Written Work</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Performance Task</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Quarterly Exam</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Initial Grade</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Final Grade</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Remarks</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($students as $student)
                                @php
                                    $grade = $grades->where('student_id', $student->id)->where('quarter', $quarter)->first();
                                    
                                    // Calculate written work percentage
                                    $writtenAssessments = $assessments->get('written_work', collect())->filter(fn($a) => $a->quarter == $quarter);
                                    $writtenPercentages = $writtenAssessments->map(function($a) use ($student) {
                                        $score = $a->scores->where('student_id', $student->id)->first();
                                        return ($score && $a->max_score > 0) ? ($score->score / $a->max_score) * 100 : null;
                                    })->filter(fn($v) => $v !== null);
                                    $writtenAvg = $writtenPercentages->count() > 0 ? $writtenPercentages->avg() : null;
                                    
                                    // Calculate performance task percentage
                                    $perfAssessments = $assessments->get('performance_task', collect())->filter(fn($a) => $a->quarter == $quarter);
                                    $perfPercentages = $perfAssessments->map(function($a) use ($student) {
                                        $score = $a->scores->where('student_id', $student->id)->first();
                                        return ($score && $a->max_score > 0) ? ($score->score / $a->max_score) * 100 : null;
                                    })->filter(fn($v) => $v !== null);
                                    $perfAvg = $perfPercentages->count() > 0 ? $perfPercentages->avg() : null;
                                    
                                    // Calculate quarterly exam percentage
                                    $examAssessment = $assessments->get('quarterly_assessment', collect())->filter(fn($a) => $a->quarter == $quarter)->first();
                                    $examScore = null;
                                    if ($examAssessment) {
                                        $score = $examAssessment->scores->where('student_id', $student->id)->first();
                                        $examScore = ($score && $examAssessment->max_score > 0) ? ($score->score / $examAssessment->max_score) * 100 : null;
                                    }
                                    
                                    // Compute preview grades on-the-fly
                                    $hasScores = $writtenAvg !== null || $perfAvg !== null || $examScore !== null;
                                    $previewInitial = null;
                                    $previewFinal = null;
                                    $previewRemarks = null;
                                    
                                    if ($hasScores) {
                                        $previewInitial = (($writtenAvg ?? 0) * $gradingWeights['written']) +
                                                         (($perfAvg ?? 0) * $gradingWeights['performance']) +
                                                         (($examScore ?? 0) * $gradingWeights['exam']);
                                        $previewInitial = max(0, min(100, round($previewInitial, 2)));
                                        $previewFinal = \App\Models\GradeTransmutation::transmute($previewInitial);
                                        $previewRemarks = \App\Models\GradeTransmutation::getRemarks($previewFinal);
                                    }
                                    
                                    // Use saved grade if submitted/approved, otherwise use preview
                                    $displayInitial = $grade ? $grade->initial_grade : $previewInitial;
                                    $displayFinal = $grade ? $grade->final_grade : $previewFinal;
                                    $displayRemarks = $grade ? $grade->remarks : $previewRemarks;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($student->avatar_path && file_exists(public_path('storage/' . $student->avatar_path)))
                                                <img src="{{ asset('storage/' . $student->avatar_path) }}" alt="{{ $student->full_name }}" class="flex-shrink-0 h-10 w-10 rounded-full object-cover">
                                            @else
                                                <div class="flex-shrink-0 h-10 w-10 bg-green-600 rounded-full flex items-center justify-center">
                                                    <span class="text-white font-semibold text-sm">
                                                        {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                                                    </span>
                                                </div>
                                            @endif
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $student->full_name }}</div>
                                                <div class="text-xs text-gray-500">{{ $student->studentProfile?->lrn ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-900">
                                        {{ $writtenAvg !== null ? number_format($writtenAvg, 2) . '%' : '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-900">
                                        {{ $perfAvg !== null ? number_format($perfAvg, 2) . '%' : '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-900">
                                        {{ $examScore !== null ? number_format($examScore, 2) . '%' : '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm font-semibold text-gray-900">
                                        @if($displayInitial !== null)
                                            {{ number_format($displayInitial, 2) }}
                                            @if(!$grade)
                                                <span class="text-xs text-gray-400 block">(preview)</span>
                                            @endif
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($displayFinal !== null)
                                            <span class="inline-block text-sm font-semibold {{ $displayFinal >= 75 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ number_format($displayFinal, 0) }}
                                            </span>
                                            @if(!$grade)
                                                <span class="text-xs text-gray-400 block">(preview)</span>
                                            @endif
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm {{ $displayRemarks === 'Passed' ? 'text-green-600 font-medium' : 'text-red-600 font-medium' }}">
                                        @if($displayRemarks)
                                            {{ $displayRemarks }}
                                            @if(!$grade)
                                                <span class="text-xs text-gray-400 block">(preview)</span>
                                            @endif
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($grade)
                                            <span class="text-xs font-semibold
                                                {{ $grade->status === 'Draft' ? 'text-gray-600' : '' }}
                                                {{ $grade->status === 'Submitted' ? 'text-blue-600' : '' }}
                                                {{ $grade->status === 'Approved' ? 'text-green-600' : '' }}
                                                {{ $grade->status === 'Returned' ? 'text-red-600' : '' }}">
                                                {{ $grade->status }}
                                            </span>
                                            @if($grade->status === 'Returned')
                                                <p class="text-xs text-red-600 mt-1">{{ $grade->return_reason }}</p>
                                            @endif
                                        @else
                                            <span class="text-gray-400 text-xs">Not graded</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($displayFinal !== null)
                                            @if(!$grade || $grade->status === 'Draft' || $grade->status === 'Returned')
                                                <form action="{{ route('teacher.grading.submit-grade', [$classSchedule, $student->id]) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="quarter" value="{{ $quarter }}">
                                                    <button type="submit" 
                                                            onclick="return confirm('Submit this grade for approval?')"
                                                            class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-medium transition">
                                                        Submit
                                                    </button>
                                                </form>
                                            @elseif($grade->status === 'Submitted')
                                                <form action="{{ route('teacher.grading.unsubmit-grade', $grade) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            onclick="return confirm('Withdraw this submission?')"
                                                            class="px-3 py-1 bg-gray-500 hover:bg-gray-600 text-white rounded text-xs font-medium transition">
                                                        Unsubmit
                                                    </button>
                                                </form>
                                            @elseif($grade->status === 'Approved')
                                                <span class="text-xs text-gray-400">Locked</span>
                                            @endif
                                        @else
                                            <span class="text-xs text-gray-400">No scores</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center text-sm text-gray-500">
                                        No students enrolled in this class
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endfor
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
