@extends('layouts.app')
@section('page_title', 'Create Track')
@section('page_subtitle', 'Add a new senior high school track.')

@section('toolbar')
    <div class="flex items-center justify-end gap-3 w-full">
        <a href="{{ route('admin.tracks.index') }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Tracks
        </a>
    </div>
@endsection

@section('content')
<div class="p-6">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-8">
                <form method="POST" action="{{ route('admin.tracks.store') }}">
                    @csrf

                    <div class="space-y-6">
                        <!-- Track Code -->
                        <div>
                            <label for="code" class="block text-sm font-semibold text-gray-900 mb-2">
                                Track Code <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="code" 
                                   id="code" 
                                   value="{{ old('code') }}"
                                   placeholder="e.g., ACAD, TVL, SPORTS"
                                   maxlength="10"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('code') border-red-500 @enderror"
                                   required>
                            @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Unique identifier for the track (max 10 characters)</p>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">
                                Description <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="description" 
                                   id="description" 
                                   value="{{ old('description') }}"
                                   placeholder="e.g., Academic Track, Technical-Vocational-Livelihood"
                                   maxlength="255"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('description') border-red-500 @enderror"
                                   required>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t">
                        <a href="{{ route('admin.tracks.index') }}" 
                           class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition text-sm font-medium">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition shadow-sm hover:shadow-md text-sm font-medium">
                            Create Track
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
