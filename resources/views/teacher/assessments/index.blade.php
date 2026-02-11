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
                <select name="class_schedule_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">All Classes</option>
                    @foreach ($classSchedules as $schedule)
                        <option value="{{ $schedule->id }}" {{ request('class_schedule_id') == $schedule->id ? 'selected' : '' }}>
                            {{ $schedule->subject->name }} - {{ $schedule->section->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="quarter" class="px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">All Quarters</option>
                    @for ($i = 1; $i <= 4; $i++)
                        <option value="{{ $i }}" {{ request('quarter') == $i ? 'selected' : '' }}>Quarter {{ $i }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">Filter</button>
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
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($assessments as $assessment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $assessment->title }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($assessment->type === 'written_work') bg-blue-100 text-blue-800
                                @elseif($assessment->type === 'performance_task') bg-purple-100 text-purple-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ str_replace('_', ' ', ucwords($assessment->type, '_')) }}
                            </span>
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
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <a href="{{ route('teacher.assessments.show', $assessment) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                            <a href="{{ route('teacher.assessments.edit', $assessment) }}" class="text-green-600 hover:text-green-900 mr-3">Edit</a>
                            <form action="{{ route('teacher.assessments.destroy', $assessment) }}" method="POST" class="inline" onsubmit="return confirm('Delete this assessment?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
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
