@extends('layouts.app')

@section('page_title', 'Create Subject')
@section('page_subtitle', 'Add a new subject to the curriculum.')

@section('content')
<div class="p-6">
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm p-8">
        <form method="POST" action="{{ route('admin.subjects.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-2 gap-6">
                <!-- Subject Code -->
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                        Subject Code *
                    </label>
                    <input type="text" name="code" id="code" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                           value="{{ old('code') }}"
                           placeholder="e.g., ENG101">
                    @error('code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Units -->
                <div>
                    <label for="units" class="block text-sm font-medium text-gray-700 mb-2">
                        Units *
                    </label>
                    <input type="number" name="units" id="units" required min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                           value="{{ old('units', 1) }}"
                           placeholder="e.g., 3">
                    @error('units')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Subject Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Subject Name *
                </label>
                <input type="text" name="name" id="name" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                       value="{{ old('name') }}"
                       placeholder="e.g., English for Academic and Professional Purposes">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Subject Type -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                    Subject Type *
                </label>
                <select name="type" id="type" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Select Type</option>
                    <option value="Core" {{ old('type') === 'Core' ? 'selected' : '' }}>Core Subject</option>
                    <option value="Applied" {{ old('type') === 'Applied' ? 'selected' : '' }}>Applied Track Subject</option>
                    <option value="Specialized" {{ old('type') === 'Specialized' ? 'selected' : '' }}>Specialized Subject</option>
                </select>
                @error('type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-blue-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm text-blue-700">
                        <p class="font-medium mb-1">Subject Types:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li><strong>Core:</strong> Required for all students (e.g., English, Math, Science, Filipino)</li>
                            <li><strong>Applied:</strong> Track-specific subjects (e.g., Pre-Calculus for STEM, Business Math for ABM)</li>
                            <li><strong>Specialized:</strong> Strand-specific subjects (e.g., General Biology for STEM)</li>
                        </ul>
                        <p class="mt-2 text-xs">Subject code must be unique across all subjects.</p>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                <a href="{{ route('admin.subjects.index') }}" class="text-gray-600 hover:text-gray-700 font-medium">
                    Cancel
                </a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition">
                    Create Subject
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
