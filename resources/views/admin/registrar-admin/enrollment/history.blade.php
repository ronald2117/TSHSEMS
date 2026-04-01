@extends('layouts.app')

@section('page_title', 'Enrollment History')
@section('page_subtitle', 'View historical student enrollment records.')

@section('content')
<div class="p-5 space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-end">
        <a href="{{ route('admin.enrollment.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-medium text-sm transition">
            ← Back to Enrollment
        </a>
    </div>

    <!-- Enrollment History Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Enrollment Records</h2>
        </div>
        
        @if($enrollments->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Student
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            LRN
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Section
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Strand
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            School Year
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Enrollment Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($enrollments as $enrollment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $enrollment->student->full_name ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $enrollment->student->studentProfile->lrn ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $enrollment->section->name ?? 'N/A' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                Grade {{ $enrollment->section->grade_level ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $enrollment->section->strand->name ?? 'N/A' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $enrollment->section->strand->code ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $enrollment->section->schoolYear->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $enrollment->created_at?->format('M d, Y') ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($enrollment->status === 'active')
                                <span class="text-xs font-semibold text-primary-600">
                                    Active
                                </span>
                            @elseif($enrollment->status === 'completed')
                                <span class="text-xs font-semibold text-blue-600">
                                    Completed
                                </span>
                            @elseif($enrollment->status === 'dropped')
                                <span class="text-xs font-semibold text-red-600">
                                    Dropped
                                </span>
                            @else
                                <span class="text-xs font-semibold text-gray-600">
                                    {{ ucfirst($enrollment->status ?? 'Unknown') }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $enrollments->links() }}
        </div>
        @else
        <div class="p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No enrollment history</h3>
            <p class="mt-1 text-sm text-gray-500">No enrollment records found for the selected filters.</p>
        </div>
        @endif
    </div>
</div>
@endsection
