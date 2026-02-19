@extends('layouts.app')
@section('page_title', 'Edit Class Schedule')
@section('page_subtitle', 'Update class schedule information.')

@section('toolbar')
    <div class="flex items-center justify-end gap-3 w-full">
        <a href="{{ route('admin.class-schedules.show', $classSchedule) }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Schedule
        </a>
    </div>
@endsection

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-8">
                <form method="POST" action="{{ route('admin.class-schedules.update', $classSchedule) }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- Academic Period -->
                        <div>
                            <label for="academic_period_id" class="block text-sm font-semibold text-gray-900 mb-2">
                                Academic Period <span class="text-red-500">*</span>
                            </label>
                            <select name="academic_period_id" 
                                    id="academic_period_id" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('academic_period_id') border-red-500 @enderror"
                                    required>
                                @foreach($academicPeriods as $period)
                                    <option value="{{ $period->id }}" {{ old('academic_period_id', $classSchedule->academic_period_id) == $period->id ? 'selected' : '' }}>
                                        {{ $period->name }} - {{ $period->schoolYear->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('academic_period_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Section -->
                        <div>
                            <label for="section_id" class="block text-sm font-semibold text-gray-900 mb-2">
                                Section <span class="text-red-500">*</span>
                            </label>
                            <select name="section_id" 
                                    id="section_id" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('section_id') border-red-500 @enderror"
                                    required>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}" {{ old('section_id', $classSchedule->section_id) == $section->id ? 'selected' : '' }}>
                                        {{ $section->name }} - {{ $section->strand->code }} (Grade {{ $section->grade_level }}) - {{ $section->schoolYear->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('section_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Subject -->
                        <div>
                            <label for="subject_id" class="block text-sm font-semibold text-gray-900 mb-2">
                                Subject <span class="text-red-500">*</span>
                            </label>
                            <select name="subject_id" 
                                    id="subject_id" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('subject_id') border-red-500 @enderror"
                                    required>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id', $classSchedule->subject_id) == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->code }} - {{ $subject->name }} ({{ $subject->subject_type }})
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Teacher -->
                        <div>
                            <label for="teacher_id" class="block text-sm font-semibold text-gray-900 mb-2">
                                Teacher <span class="text-red-500">*</span>
                            </label>
                            <select name="teacher_id" 
                                    id="teacher_id" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('teacher_id') border-red-500 @enderror"
                                    required>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id', $classSchedule->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Schedule Time -->
                        <div>
                            <label for="schedule_time" class="block text-sm font-semibold text-gray-900 mb-2">
                                Schedule Time (Optional)
                            </label>
                            <input type="text" 
                                   name="schedule_time" 
                                   id="schedule_time" 
                                   value="{{ old('schedule_time', $classSchedule->schedule_time) }}"
                                   placeholder="e.g., Mon/Wed/Fri 8:00-9:00 AM"
                                   maxlength="100"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('schedule_time') border-red-500 @enderror">
                            @error('schedule_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Room -->
                        <div>
                            <label for="room" class="block text-sm font-semibold text-gray-900 mb-2">
                                Room (Optional)
                            </label>
                            <input type="text" 
                                   name="room" 
                                   id="room" 
                                   value="{{ old('room', $classSchedule->room) }}"
                                   placeholder="e.g., Room 101, Science Lab"
                                   maxlength="50"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('room') border-red-500 @enderror">
                            @error('room')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t">
                        <a href="{{ route('admin.class-schedules.show', $classSchedule) }}" 
                           class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition text-sm font-medium">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition shadow-sm hover:shadow-md text-sm font-medium">
                            Update Schedule
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
