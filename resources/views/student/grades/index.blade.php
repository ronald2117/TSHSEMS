@extends('layouts.app')

@section('page_title', 'Student Grades')
@section('page_subtitle', 'View your approved grades by subject and quarter.')

@section('content')
<div class="p-6">
    @if($grades->isEmpty())
        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Grades Available</h3>
            <p class="text-gray-600">Your grades will appear here once they've been approved by your registrar.</p>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Subject</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Section</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Quarter</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Grade</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Status</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($grades as $grade)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $grade->classSchedule->subject->name }}</p>
                                <p class="text-sm text-gray-600">{{ $grade->classSchedule->subject->type }}</p>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $grade->classSchedule->section->name }}</td>
                            <td class="px-6 py-4 text-center font-medium text-gray-900">Q{{ $grade->quarter }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-semibold text-lg text-green-600">
                                    {{ $grade->final_grade }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs font-semibold {{ $grade->remarks === 'Passed' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $grade->remarks }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('student.grades.show', $grade) }}" class="text-green-600 hover:text-green-700 font-medium text-sm">
                                    View Details
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $grades->links() }}
        </div>
    @endif
</div>
@endsection
