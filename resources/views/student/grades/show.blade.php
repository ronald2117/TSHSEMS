@extends('layouts.app')

@section('page_title', 'Grade Details')
@section('page_subtitle', 'Review your grade for this subject.')

@section('content')
<div class="p-6">
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm p-8">
        <!-- Grade Header -->
        <div class="border-b border-gray-100 pb-6 mb-6">
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Subject</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $grade->classSchedule->subject->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Quarter</p>
                    <p class="text-xl font-semibold text-gray-900">Q{{ $grade->quarter }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Teacher</p>
                    <p class="text-lg text-gray-900">{{ $grade->classSchedule->teacher->full_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Section</p>
                    <p class="text-lg text-gray-900">{{ $grade->classSchedule->section->name }}</p>
                </div>
            </div>
        </div>

        <!-- Grade Information -->
        <div class="grid grid-cols-3 gap-6 mb-8">
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-2">Initial Grade</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($grade->initial_grade, 2) }}</p>
                <p class="text-xs text-gray-500 mt-1">(Percentage)</p>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-2">Final Grade</p>
                <p class="text-4xl font-bold text-green-600">{{ $grade->final_grade }}</p>
                <p class="text-xs text-gray-500 mt-1">(Transmuted)</p>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-2">Remarks</p>
                <p class="text-lg font-semibold {{ $grade->remarks === 'Passed' ? 'text-green-600' : 'text-red-600' }}">
                    {{ $grade->remarks }}
                </p>
                <p class="text-xs text-gray-500 mt-1">{{ $grade->remarks === 'Passed' ? 'Passing' : 'Not passing' }}</p>
            </div>
        </div>

        <!-- Status Information -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8">
            <p class="text-sm text-gray-600 mb-3">Grade Status</p>
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-gray-700">Current Status:</span>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                        {{ $grade->status }}
                    </span>
                </div>
                @if($grade->approved_at)
                <div class="flex items-center justify-between">
                    <span class="text-gray-700">Approved At:</span>
                    <span class="text-gray-900">{{ $grade->approved_at->format('M d, Y H:i') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-700">Approved By:</span>
                    <span class="text-gray-900">{{ $grade->approver->full_name }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Audit History -->
        @if($grade->auditLogs->isNotEmpty())
        <div class="border-t border-gray-100 pt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Grade Change History</h3>
            <div class="space-y-3">
                @foreach($grade->auditLogs as $log)
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <p class="font-medium text-gray-900">{{ $log->user->full_name }}</p>
                            <p class="text-sm text-gray-600">{{ $log->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">
                            {{ str_replace('_', ' ', ucfirst($log->field_changed)) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">{{ $log->reason }}</p>
                    <p class="text-xs text-gray-500">IP: {{ $log->ip_address }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Back Button -->
        <div class="mt-8 pt-6 border-t border-gray-100">
            <a href="{{ route('student.grades.index') }}" class="text-green-600 hover:text-green-700 font-medium">
                ← Back to Grades
            </a>
        </div>
    </div>
</div>
@endsection
