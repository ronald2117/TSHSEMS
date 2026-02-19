@extends('layouts.app')

@section('page_title', 'Edit School Year')
@section('page_subtitle', 'Update academic school year information.')

@section('content')
<div class="p-6">
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm p-8">
        <form method="POST" action="{{ route('admin.school-years.update', $schoolYear) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- School Year Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    School Year Name *
                </label>
                <input type="text" name="name" id="name" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                       value="{{ old('name', $schoolYear->name) }}"
                       placeholder="e.g., 2025-2026">
                <p class="text-xs text-gray-500 mt-1">Format: YYYY-YYYY (e.g., 2025-2026)</p>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-6">
                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Start Date *
                    </label>
                    <input type="date" name="start_date" id="start_date" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                           value="{{ old('start_date', $schoolYear->start_date->format('Y-m-d')) }}">
                    @error('start_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                        End Date *
                    </label>
                    <input type="date" name="end_date" id="end_date" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                           value="{{ old('end_date', $schoolYear->end_date->format('Y-m-d')) }}">
                    @error('end_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Active Status -->
            <div>
                <label for="is_active" class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                           class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-green-500"
                           {{ old('is_active', $schoolYear->is_active) ? 'checked' : '' }}>
                    <span class="ml-2 text-sm font-medium text-gray-700">Set as Active School Year</span>
                </label>
                <p class="text-xs text-gray-500 mt-1">Only one school year can be active at a time. Activating this will deactivate others.</p>
                @error('is_active')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Status Info -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm text-gray-700">
                        <p class="font-medium mb-1">Current Status:</p>
                        <p>Status: 
                            @if($schoolYear->is_active)
                                <span class="text-primary-600 font-semibold">Active</span>
                            @else
                                <span class="text-gray-600 font-semibold">Inactive</span>
                            @endif
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Created: {{ $schoolYear->created_at->format('M d, Y') }}</p>
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
                            <li>End date must be after start date</li>
                            <li>School year typically runs from June to March/April</li>
                            <li>Changing dates may affect existing academic periods and schedules</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                <a href="{{ route('admin.school-years.index') }}" class="text-gray-600 hover:text-gray-700 font-medium">
                    Cancel
                </a>
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg font-medium transition">
                    Update School Year
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
