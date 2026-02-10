@extends('layouts.app')
@section('page_title', 'Enroll Student')
@section('page_subtitle', 'Assign student to a section')

@section('content')
<div class="p-6">
    <div class="max-w-2xl mx-auto">
        <!-- Student Information Card -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Student Information</h2>
            </div>
            <div class="p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                        <span class="text-2xl font-bold text-green-600">
                            {{ substr($student->user->first_name ?? 'S', 0, 1) }}{{ substr($student->user->last_name ?? '', 0, 1) }}
                        </span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $student->user->full_name }}</h3>
                        <p class="text-sm text-gray-600">LRN: {{ $student->lrn }}</p>
                        <p class="text-sm text-gray-600">
                            Current Section: 
                            <span class="font-medium">
                                {{ $student->currentSection->name ?? 'Not Enrolled' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enrollment Form -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Enrollment Details</h2>
                <p class="mt-1 text-sm text-gray-600">Assign this student to a section for the current school year.</p>
            </div>

            @if($errors->any())
                <div class="p-4 bg-red-50 border-b border-red-200">
                    <div class="flex">
                        <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ $errors->first() }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.enrollment.process', $student->id) }}" method="POST" class="p-6 space-y-6">
                @csrf

                <!-- Section Selection -->
                <div>
                    <label for="section_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Section <span class="text-red-500">*</span>
                    </label>
                    <select name="section_id" id="section_id" required
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('section_id') border-red-500 @enderror">
                        <option value="">Select a Section</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                Grade {{ $section->grade_level }} - {{ $section->name }} 
                                ({{ $section->strand->name ?? 'No Strand' }})
                                - {{ $section->schoolYear->name ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('section_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    @if($sections->isEmpty())
                        <p class="mt-2 text-sm text-yellow-600">
                            <svg class="inline w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            No sections available for the current school year. Please create sections first.
                        </p>
                    @endif
                </div>

                <!-- Enrollment Date -->
                <div>
                    <label for="enrollment_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Enrollment Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="enrollment_date" id="enrollment_date" required
                           value="{{ old('enrollment_date', date('Y-m-d')) }}"
                           class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('enrollment_date') border-red-500 @enderror">
                    @error('enrollment_date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                Upon enrollment, the student will automatically be enrolled in all subjects assigned to the selected section.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.enrollment.index') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-4 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition {{ $sections->isEmpty() ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ $sections->isEmpty() ? 'disabled' : '' }}>
                        Enroll Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
