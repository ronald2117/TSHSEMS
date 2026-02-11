@extends('layouts.app')

@section('page_title', 'Edit Assessment')
@section('page_subtitle', 'Modify assessment details and settings.')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <a href="{{ route('teacher.assessments.index') }}" class="text-green-600 hover:text-green-700 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Assessments
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Assessment</h1>

        <form action="{{ route('teacher.assessments.update', $assessment) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="class_schedule_id" class="block text-sm font-medium text-gray-700 mb-2">Class *</label>
                <select name="class_schedule_id" 
                        id="class_schedule_id" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('class_schedule_id') border-red-500 @enderror" 
                        required>
                    <option value="">Select a class</option>
                    @foreach ($classSchedules as $schedule)
                        <option value="{{ $schedule->id }}" {{ old('class_schedule_id', $assessment->class_schedule_id) == $schedule->id ? 'selected' : '' }}>
                            {{ $schedule->subject->name }} - {{ $schedule->section->name }} (Grade {{ $schedule->section->grade_level }})
                        </option>
                    @endforeach
                </select>
                @error('class_schedule_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Assessment Title *</label>
                <input type="text" 
                       name="title" 
                       id="title" 
                       value="{{ old('title', $assessment->title) }}"
                       placeholder="e.g., Quiz #1, Performance Task #2"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('title') border-red-500 @enderror" 
                       required>
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                    <select name="type" 
                            id="type" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('type') border-red-500 @enderror" 
                            required>
                        <option value="">Select type</option>
                        <option value="written_work" {{ old('type', $assessment->type) == 'written_work' ? 'selected' : '' }}>Written Work</option>
                        <option value="performance_task" {{ old('type', $assessment->type) == 'performance_task' ? 'selected' : '' }}>Performance Task</option>
                        <option value="quarterly_assessment" {{ old('type', $assessment->type) == 'quarterly_assessment' ? 'selected' : '' }}>Quarterly Assessment</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="max_score" class="block text-sm font-medium text-gray-700 mb-2">Maximum Score *</label>
                    <input type="number" 
                           name="max_score" 
                           id="max_score" 
                           value="{{ old('max_score', $assessment->max_score) }}"
                           min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('max_score') border-red-500 @enderror" 
                           required>
                    @error('max_score')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="quarter" class="block text-sm font-medium text-gray-700 mb-2">Quarter *</label>
                    <select name="quarter" 
                            id="quarter" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('quarter') border-red-500 @enderror" 
                            required>
                        @for ($i = 1; $i <= 4; $i++)
                            <option value="{{ $i }}" {{ old('quarter', $assessment->quarter) == $i ? 'selected' : '' }}>Quarter {{ $i }}</option>
                        @endfor
                    </select>
                    @error('quarter')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="assessment_date" class="block text-sm font-medium text-gray-700 mb-2">Assessment Date *</label>
                <input type="date" 
                       name="assessment_date" 
                       id="assessment_date" 
                       value="{{ old('assessment_date', $assessment->assessment_date ? $assessment->assessment_date->format('Y-m-d') : '') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('assessment_date') border-red-500 @enderror" 
                       required>
                @error('assessment_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" 
                           name="is_published" 
                           value="1"
                           {{ old('is_published', $assessment->is_published) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                    <span class="ml-2 text-sm text-gray-700">Publish assessment (visible to students)</span>
                </label>
            </div>

            <div class="flex gap-4">
                <button type="submit" 
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Update Assessment
                </button>
                <a href="{{ route('teacher.assessments.index') }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
