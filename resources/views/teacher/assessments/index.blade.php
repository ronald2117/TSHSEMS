@extends('layouts.app')

@section('page_title', 'My Assessments')
@section('page_subtitle', 'View and manage your class assessments.')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">My Assessments</h1>
        <a href="{{ route('teacher.assessments.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Create Assessment
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('teacher.assessments.index') }}" class="flex gap-4">
            <div class="flex-1">
                <select name="class_schedule_id" class="cursor-pointer w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">All Classes</option>
                    @foreach ($classSchedules as $schedule)
                        <option value="{{ $schedule->id }}" {{ request('class_schedule_id') == $schedule->id ? 'selected' : '' }}>
                            {{ $schedule->subject->name }} - {{ $schedule->section->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="quarter" class="cursor-pointer px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">All Quarters</option>
                    @for ($i = 1; $i <= 4; $i++)
                        <option value="{{ $i }}" {{ request('quarter') == $i ? 'selected' : '' }}>Quarter {{ $i }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="cursor-pointer bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quarter</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Max Score</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($assessments as $assessment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $assessment->title }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ str_replace('_', ' ', ucwords($assessment->type, '_')) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $assessment->classSchedule->subject->name }}<br>
                            <span class="text-xs">{{ $assessment->classSchedule->section->name }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">Q{{ $assessment->quarter }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $assessment->max_score }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $assessment->assessment_date ? $assessment->assessment_date->format('M d, Y') : 'N/A' }}</td>
                        <td class="px-6 py-4">
                            @if ($assessment->is_published)
                                <span class="text-xs font-semibold text-green-600">Published</span>
                            @else
                                <span class="text-xs font-semibold text-yellow-600">Draft</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-3">
                                <a href="{{ route('teacher.assessments.show', $assessment) }}" 
                                   class="text-gray-600 hover:text-blue-600 transition" 
                                   title="View Details">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('teacher.assessments.edit', $assessment) }}" 
                                   class="text-gray-600 hover:text-green-600 transition" 
                                   title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ route('teacher.assessments.destroy', $assessment) }}" method="POST" class="inline" onsubmit="return confirm('Delete this assessment?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="cursor-pointer text-gray-600 hover:text-red-600 transition" 
                                            title="Delete">
                                        <svg class="mt-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            No assessments found. <a href="{{ route('teacher.assessments.create') }}" class="text-green-600 hover:underline">Create one now</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $assessments->links() }}
    </div>
</div>
@endsection
