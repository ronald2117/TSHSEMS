@extends('layouts.app')

@section('page_title', 'Edit Section')
@section('page_subtitle', 'Update section information.')

@section('content')
<div class="p-6">
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm p-8">
        <form method="POST" action="{{ route('admin.sections.update', $section) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Section Name *
                </label>
                <input type="text" name="name" id="name" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                       value="{{ old('name', $section->name) }}"
                       placeholder="e.g., Diamond, Sapphire, Emerald">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-6">
                <!-- Grade Level -->
                <div>
                    <label for="grade_level" class="block text-sm font-medium text-gray-700 mb-2">
                        Grade Level *
                    </label>
                    <select name="grade_level" id="grade_level" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Select Grade Level</option>
                        <option value="11" {{ old('grade_level', $section->grade_level) == 11 ? 'selected' : '' }}>Grade 11</option>
                        <option value="12" {{ old('grade_level', $section->grade_level) == 12 ? 'selected' : '' }}>Grade 12</option>
                    </select>
                    @error('grade_level')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Max Students -->
                <div>
                    <label for="max_students" class="block text-sm font-medium text-gray-700 mb-2">
                        Maximum Students *
                    </label>
                    <input type="number" name="max_students" id="max_students" required min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                           value="{{ old('max_students', $section->max_students) }}"
                           placeholder="e.g., 40">
                    @error('max_students')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- School Year -->
            <div>
                <label for="school_year_id" class="block text-sm font-medium text-gray-700 mb-2">
                    School Year *
                </label>
                <select name="school_year_id" id="school_year_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Select School Year</option>
                    @foreach($schoolYears as $schoolYear)
                        <option value="{{ $schoolYear->id }}" {{ old('school_year_id', $section->school_year_id) == $schoolYear->id ? 'selected' : '' }}>
                            {{ $schoolYear->name }}
                        </option>
                    @endforeach
                </select>
                @error('school_year_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Strand -->
            <div>
                <label for="strand_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Strand *
                </label>
                <select name="strand_id" id="strand_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Select Strand</option>
                    @foreach($strands as $strand)
                        <option value="{{ $strand->id }}" {{ old('strand_id', $section->strand_id) == $strand->id ? 'selected' : '' }}>
                            {{ $strand->name }} ({{ $strand->code }})
                        </option>
                    @endforeach
                </select>
                @error('strand_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Info Box -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm text-gray-700">
                        <p class="font-medium mb-1">Current Section Info:</p>
                        <p>Created: {{ $section->created_at->format('M d, Y') }}</p>
                        @if($section->adviser_id)
                            <p>Adviser: {{ $section->adviser->first_name }} {{ $section->adviser->last_name }}</p>
                        @else
                            <p class="text-gray-500">No adviser assigned yet</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-blue-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm text-blue-700">
                        <p class="font-medium mb-1">Important Notes:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Section name should be unique within the school year</li>
                            <li>Maximum students helps prevent over-enrollment</li>
                            <li>Changing strand may affect subject enrollments</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                <a href="{{ route('admin.sections.index') }}" class="text-gray-600 hover:text-gray-700 font-medium">
                    Cancel
                </a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition">
                    Update Section
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
