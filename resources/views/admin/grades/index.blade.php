@extends('layouts.app')

@section('page_title', 'Grade Approval')
@section('page_subtitle', 'Review and approve submitted quarterly grades')

@section('content')
<div class="p-6">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Pending Grade Approvals</h2>
            <p class="text-sm text-gray-500 mt-1">Review grades submitted by teachers for approval</p>
        </div>

        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quarter</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Final Grade</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($grades as $grade)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($grade->student)
                            <div class="flex items-center">
                                @if($grade->student->avatar_path && file_exists(public_path('storage/' . $grade->student->avatar_path)))
                                    <img src="{{ asset('storage/' . $grade->student->avatar_path) }}" alt="{{ $grade->student->full_name }}" class="flex-shrink-0 h-10 w-10 rounded-full object-cover">
                                @else
                                    <div class="flex-shrink-0 h-10 w-10 bg-green-600 rounded-full flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">
                                            {{ strtoupper(substr($grade->student->first_name, 0, 1)) }}{{ strtoupper(substr($grade->student->last_name, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $grade->student->full_name }}
                                        @if($grade->student->trashed())
                                            <span class="text-xs text-red-500 ml-1">(deleted)</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $grade->student->studentProfile->lrn ?? 'N/A' }}</div>
                                </div>
                            </div>
                        @else
                            <div class="text-sm text-gray-400 italic">Student record not found</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $grade->classSchedule->subject->name }}</div>
                        <div class="text-xs text-gray-500">{{ $grade->classSchedule->subject->code }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">Quarter {{ $grade->quarter }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-semibold {{ $grade->final_grade >= 75 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($grade->final_grade, 2) }}
                        </div>
                        <div class="text-xs text-gray-500">{{ $grade->remarks }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($grade->status === 'Submitted')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                Submitted
                            </span>
                        @elseif($grade->status === 'Draft')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Draft
                            </span>
                        @elseif($grade->status === 'Returned')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Returned
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $grade->submitted_at?->format('M d, Y') ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('admin.grade-approval.show', $grade) }}" class="text-blue-600 hover:text-blue-900">
                            Review
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="mt-2 text-sm font-medium">No grades pending approval</p>
                        <p class="text-xs text-gray-400 mt-1">All submitted grades have been processed</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($grades->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $grades->links() }}
        </div>
        @endif
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 bg-blue-100 rounded-lg">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Pending Review</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $grades->total() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
