@extends('layouts.app')

@section('page_title', 'Review Grade')
@section('page_subtitle', 'Review and approve or return this grade submission')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Student & Subject Info -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Student Information</h3>
                    @if($grade->student)
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $grade->student->first_name }} {{ $grade->student->last_name }}
                        </p>
                        <p class="text-sm text-gray-500">LRN: {{ $grade->student->studentProfile->lrn ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">Section: {{ $grade->student->studentProfile->section->name ?? 'N/A' }}</p>
                    @else
                        <p class="text-lg font-semibold text-red-600">Student Record Not Found</p>
                        <p class="text-sm text-gray-500">The student associated with this grade no longer exists.</p>
                    @endif
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Subject Information</h3>
                    @if($grade->classSchedule && $grade->classSchedule->subject)
                        <p class="text-lg font-semibold text-gray-900">{{ $grade->classSchedule->subject->name }}</p>
                        <p class="text-sm text-gray-500">Code: {{ $grade->classSchedule->subject->code }}</p>
                        <p class="text-sm text-gray-500">Quarter: {{ $grade->quarter }}</p>
                    @else
                        <p class="text-lg font-semibold text-red-600">Subject Record Not Found</p>
                        <p class="text-sm text-gray-500">The class schedule or subject no longer exists.</p>
                        <p class="text-sm text-gray-500">Quarter: {{ $grade->quarter }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Grade Breakdown -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Grade Breakdown</h3>
            
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-xs font-medium text-blue-600 mb-1">Written Work</p>
                    <p class="text-2xl font-bold text-blue-900">{{ number_format($grade->written_work_score ?? 0, 2) }}</p>
                </div>
                <div class="bg-green-50 rounded-lg p-4">
                    <p class="text-xs font-medium text-green-600 mb-1">Performance Task</p>
                    <p class="text-2xl font-bold text-green-900">{{ number_format($grade->performance_task_score ?? 0, 2) }}</p>
                </div>
                <div class="bg-purple-50 rounded-lg p-4">
                    <p class="text-xs font-medium text-purple-600 mb-1">Quarterly Assessment</p>
                    <p class="text-2xl font-bold text-purple-900">{{ number_format($grade->quarterly_assessment_score ?? 0, 2) }}</p>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-600">Initial Grade:</span>
                    <span class="text-lg font-semibold text-gray-900">{{ number_format($grade->initial_grade, 2) }}</span>
                </div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-600">Final Grade:</span>
                    <span class="text-2xl font-bold {{ $grade->final_grade >= 75 ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format($grade->final_grade, 2) }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Remarks:</span>
                    <span class="text-sm font-medium {{ $grade->remarks === 'Passed' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $grade->remarks }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Status & Actions -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Grade Status</h3>
            
            <div class="mb-4">
                <div class="flex items-center mb-2">
                    <span class="text-sm text-gray-600 w-32">Current Status:</span>
                    @if($grade->status === 'Submitted')
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            Submitted for Approval
                        </span>
                    @elseif($grade->status === 'Returned')
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Returned to Teacher
                        </span>
                    @elseif($grade->status === 'Draft')
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                            Draft
                        </span>
                    @endif
                </div>
                <div class="flex items-center">
                    <span class="text-sm text-gray-600 w-32">Submitted At:</span>
                    <span class="text-sm text-gray-900">{{ $grade->submitted_at?->format('M d, Y h:i A') ?? 'Not submitted' }}</span>
                </div>
            </div>

            @if($grade->status === 'Submitted' && $grade->id)
            <div class="border-t border-gray-200 pt-4">
                <div class="flex gap-4">
                    <!-- Approve Button -->
                    <form action="{{ route('admin.grade-approval.approve', $grade) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Are you sure you want to approve this grade?')"
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition">
                            ✓ Approve Grade
                        </button>
                    </form>

                    <!-- Return Button -->
                    <button type="button" 
                            onclick="document.getElementById('returnModal').classList.remove('hidden')"
                            class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg font-medium transition">
                        ↩ Return to Teacher
                    </button>
                </div>
            </div>
            @endif
        </div>

        <!-- Audit Log -->
        @if($grade->auditLogs->count() > 0)
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Audit Trail</h3>
            <div class="space-y-3">
                @foreach($grade->auditLogs as $log)
                <div class="flex items-start border-l-2 border-gray-300 pl-4">
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">{{ $log->reason }}</p>
                        <p class="text-xs text-gray-500">
                            {{ $log->user->first_name }} {{ $log->user->last_name }} • 
                            {{ $log->created_at->format('M d, Y h:i A') }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('admin.grade-approval.index') }}" class="text-gray-600 hover:text-gray-700 font-medium">
                ← Back to Grade Approvals
            </a>
        </div>
    </div>
</div>

<!-- Return Modal -->
@if($grade && $grade->id)
<div id="returnModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Return Grade to Teacher</h3>
        
        <form action="{{ route('admin.grade-approval.return', $grade) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="return_reason" class="block text-sm font-medium text-gray-700 mb-2">
                    Reason for Return *
                </label>
                <textarea name="return_reason" id="return_reason" rows="4" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500"
                          placeholder="Please explain why this grade is being returned..."></textarea>
            </div>
            
            <div class="flex justify-end gap-3">
                <button type="button" 
                        onclick="document.getElementById('returnModal').classList.add('hidden')"
                        class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg font-medium">
                    Return Grade
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
