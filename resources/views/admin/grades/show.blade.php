@extends('layouts.app')

@section('page_title', 'Review Grade')
@section('page_subtitle', 'Review and approve or return this grade submission')

@section('content')
<div class="p-6">
    <div class="max-w-5xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('admin.grade-approval.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Grade Approvals
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Student & Subject Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Student Information Card -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Student Information</h3>
                    @if($grade->student)
                        <div class="flex items-center gap-4">
                            @if($grade->student->avatar_path && file_exists(public_path('storage/' . $grade->student->avatar_path)))
                                <img src="{{ asset('storage/' . $grade->student->avatar_path) }}" alt="{{ $grade->student->full_name }}" class="w-16 h-16 rounded-full object-cover">
                            @else
                                <div class="w-16 h-16 bg-primary-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold text-xl">
                                        {{ strtoupper(substr($grade->student->first_name, 0, 1)) }}{{ strtoupper(substr($grade->student->last_name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                            <div class="flex-1">
                                <p class="text-xl font-semibold text-gray-900">
                                    {{ $grade->student->full_name }}
                                    @if($grade->student->trashed())
                                        <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full ml-2">Deleted</span>
                                    @endif
                                </p>
                                <div class="mt-1 grid grid-cols-2 gap-x-4 gap-y-1 text-sm text-gray-600">
                                    <p><span class="text-gray-400">LRN:</span> {{ $grade->student->studentProfile->lrn ?? 'N/A' }}</p>
                                    <p><span class="text-gray-400">Email:</span> {{ $grade->student->email ?? 'N/A' }}</p>
                                    <p><span class="text-gray-400">Section:</span> {{ $grade->student->studentProfile->currentSection->name ?? 'N/A' }}</p>
                                    <p><span class="text-gray-400">Grade Level:</span> {{ $grade->student->studentProfile->currentSection->grade_level ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-4 text-red-600">
                            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-lg font-semibold">Student Record Not Found</p>
                                <p class="text-sm text-gray-500">The student associated with this grade no longer exists in the system.</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Subject Information Card -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Subject & Class Information</h3>
                    @if($grade->classSchedule && $grade->classSchedule->subject)
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-400">Subject</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $grade->classSchedule->subject->name }}</p>
                                <p class="text-sm text-gray-600">{{ $grade->classSchedule->subject->code }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">Section</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $grade->classSchedule->section->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">Teacher</p>
                                <p class="font-medium text-gray-900">{{ $grade->classSchedule->teacher->full_name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">Academic Period</p>
                                <p class="font-medium text-gray-900">
                                    {{ $grade->classSchedule->academicPeriod->name ?? 'N/A' }}
                                    @if($grade->classSchedule->academicPeriod?->schoolYear)
                                        <span class="text-sm text-gray-500">({{ $grade->classSchedule->academicPeriod->schoolYear->name }})</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">Quarter</p>
                                <p class="font-medium text-gray-900">Quarter {{ $grade->quarter }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">Subject Type</p>
                                <p class="font-medium text-gray-900 capitalize">{{ $grade->classSchedule->subject->type ?? 'Core' }}</p>
                            </div>
                        </div>
                    @else
                        <div class="text-red-600">
                            <p class="text-lg font-semibold">Subject/Class Record Not Found</p>
                            <p class="text-sm text-gray-500">The class schedule or subject no longer exists.</p>
                            <p class="text-sm text-gray-600 mt-2">Quarter: {{ $grade->quarter }}</p>
                        </div>
                    @endif
                </div>

                <!-- Grade Breakdown Card -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Grade Breakdown</h3>
                    
                    <!-- Component Scores -->
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <p class="text-xs font-medium text-blue-600 uppercase mb-1">Written Work</p>
                            <p class="text-2xl font-bold text-blue-900">
                                {{ isset($assessmentScores['written_work']['average']) ? number_format($assessmentScores['written_work']['average'], 2) . '%' : '—' }}
                            </p>
                            <p class="text-xs text-blue-500 mt-1">{{ $assessmentScores['written_work']['count'] ?? 0 }} assessment(s)</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4 text-center">
                            <p class="text-xs font-medium text-primary-600 uppercase mb-1">Performance Task</p>
                            <p class="text-2xl font-bold text-green-900">
                                {{ isset($assessmentScores['performance_task']['average']) ? number_format($assessmentScores['performance_task']['average'], 2) . '%' : '—' }}
                            </p>
                            <p class="text-xs text-green-500 mt-1">{{ $assessmentScores['performance_task']['count'] ?? 0 }} assessment(s)</p>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-4 text-center">
                            <p class="text-xs font-medium text-purple-600 uppercase mb-1">Quarterly Exam</p>
                            <p class="text-2xl font-bold text-purple-900">
                                {{ isset($assessmentScores['quarterly_assessment']['average']) ? number_format($assessmentScores['quarterly_assessment']['average'], 2) . '%' : '—' }}
                            </p>
                            <p class="text-xs text-purple-500 mt-1">{{ $assessmentScores['quarterly_assessment']['count'] ?? 0 }} assessment(s)</p>
                        </div>
                    </div>

                    <!-- Final Grades -->
                    <div class="border-t border-gray-200 pt-4 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Initial Grade (Weighted Average):</span>
                            <span class="text-lg font-semibold text-gray-900">{{ number_format($grade->initial_grade, 2) }}%</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Final Grade (Transmuted):</span>
                            <span class="text-3xl font-bold {{ $grade->final_grade >= 75 ? 'text-primary-600' : 'text-red-600' }}">
                                {{ number_format($grade->final_grade, 0) }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Remarks:</span>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $grade->remarks === 'Passed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $grade->remarks }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Audit Trail -->
                @if($grade->auditLogs && $grade->auditLogs->count() > 0)
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Audit Trail</h3>
                    <div class="space-y-4">
                        @foreach($grade->auditLogs as $log)
                            <div class="flex items-start gap-3 pb-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-900">{{ $log->reason }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        @if($log->user)
                                            {{ $log->user->full_name }} •
                                        @endif
                                        {{ $log->created_at->format('M d, Y h:i A') }}
                                        @if($log->old_grade && $log->new_grade)
                                            <span class="text-gray-400">| Grade: {{ $log->old_grade }} → {{ $log->new_grade }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column: Status & Actions -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Status</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Current Status</p>
                            @if($grade->status === 'Submitted')
                                <span class="inline-flex items-center px-3 py-1.5 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"></path>
                                    </svg>
                                    Pending Approval
                                </span>
                            @elseif($grade->status === 'Approved')
                                <span class="inline-flex items-center px-3 py-1.5 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Approved
                                </span>
                            @elseif($grade->status === 'Returned')
                                <span class="inline-flex items-center px-3 py-1.5 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L9.414 11H13a1 1 0 100-2H9.414l1.293-1.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    Returned
                                </span>
                            @elseif($grade->status === 'Draft')
                                <span class="inline-flex items-center px-3 py-1.5 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                    </svg>
                                    Draft
                                </span>
                            @endif
                        </div>

                        @if($grade->return_reason)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                <p class="text-xs font-medium text-yellow-800 mb-1">Return Reason:</p>
                                <p class="text-sm text-yellow-700">{{ $grade->return_reason }}</p>
                            </div>
                        @endif

                        <div class="border-t border-gray-100 pt-4 space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Submitted:</span>
                                <span class="text-gray-900">{{ $grade->submitted_at?->format('M d, Y h:i A') ?? '—' }}</span>
                            </div>
                            @if($grade->submitter)
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Submitted by:</span>
                                    <span class="text-gray-900">{{ $grade->submitter->full_name }}</span>
                                </div>
                            @endif
                            @if($grade->approved_at)
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Approved:</span>
                                    <span class="text-gray-900">{{ $grade->approved_at->format('M d, Y h:i A') }}</span>
                                </div>
                            @endif
                            @if($grade->approver)
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Approved by:</span>
                                    <span class="text-gray-900">{{ $grade->approver->full_name }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actions Card -->
                @if($grade->status === 'Submitted')
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Actions</h3>
                    
                    <div class="space-y-3">
                        <!-- Approve Button -->
                        <form action="{{ route('admin.grade-approval.approve', $grade) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    onclick="return confirm('Are you sure you want to approve this grade?')"
                                    class="cursor-pointer w-full flex items-center justify-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-4 py-3 rounded-lg font-medium transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Approve Grade
                            </button>
                        </form>

                        <!-- Return Button -->
                        <button type="button" 
                                onclick="document.getElementById('returnModal').classList.remove('hidden')"
                                class="cursor-pointer w-full flex items-center justify-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-3 rounded-lg font-medium transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                            </svg>
                            Return to Teacher
                        </button>

                        <!-- Override Button -->
                        <button type="button" 
                                onclick="document.getElementById('overrideModal').classList.remove('hidden')"
                                class="cursor-pointer w-full flex items-center justify-center gap-2 border-2 border-red-300 text-red-600 hover:bg-red-50 px-4 py-3 rounded-lg font-medium transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Override Grade
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Return Modal -->
<div id="returnModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
    <div class="relative mx-auto p-6 border w-full max-w-md shadow-xl rounded-xl bg-white">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Return Grade to Teacher</h3>
            <button onclick="document.getElementById('returnModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form action="{{ route('admin.grade-approval.return', $grade) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="return_reason" class="block text-sm font-medium text-gray-700 mb-2">
                    Reason for Return <span class="text-red-500">*</span>
                </label>
                <textarea name="return_reason" id="return_reason" rows="4" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                          placeholder="Please explain why this grade is being returned..."></textarea>
            </div>
            
            <div class="flex justify-end gap-3">
                <button type="button" 
                        onclick="document.getElementById('returnModal').classList.add('hidden')"
                        class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg font-medium transition">
                    Return Grade
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Override Modal -->
<div id="overrideModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
    <div class="relative mx-auto p-6 border w-full max-w-md shadow-xl rounded-xl bg-white">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Override Grade</h3>
            <button onclick="document.getElementById('overrideModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
            <p class="text-sm text-red-700">
                <strong>Warning:</strong> This action will permanently change the student's grade. Please ensure you have proper authorization.
            </p>
        </div>
        
        <form action="{{ route('admin.grade-approval.override', $grade) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="new_grade" class="block text-sm font-medium text-gray-700 mb-2">
                    New Grade (60-100) <span class="text-red-500">*</span>
                </label>
                <input type="number" name="new_grade" id="new_grade" min="60" max="100" required
                       value="{{ $grade->final_grade }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
            </div>
            <div class="mb-4">
                <label for="override_reason" class="block text-sm font-medium text-gray-700 mb-2">
                    Reason for Override <span class="text-red-500">*</span>
                </label>
                <textarea name="reason" id="override_reason" rows="4" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                          placeholder="Explain why this grade needs to be overridden..."></textarea>
            </div>
            
            <div class="flex justify-end gap-3">
                <button type="button" 
                        onclick="document.getElementById('overrideModal').classList.add('hidden')"
                        class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition">
                    Cancel
                </button>
                <button type="submit" 
                        onclick="return confirm('Are you absolutely sure you want to override this grade? This action will be logged.')"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition">
                    Override & Approve
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
