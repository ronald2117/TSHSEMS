@extends('layouts.app')
@section('page_title', 'Create Strand')
@section('page_subtitle', 'Add a new strand to a track.')

@section('toolbar')
    <div class="flex items-center justify-end gap-3 w-full">
        <a href="{{ route('admin.strands.index') }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Strands
        </a>
    </div>
@endsection

@section('content')
<div class="p-6">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-8">
                <form method="POST" action="{{ route('admin.strands.store') }}">
                    @csrf

                    <div class="space-y-6">
                        <!-- Track Selection -->
                        <div>
                            <label for="track_id" class="block text-sm font-semibold text-gray-900 mb-2">
                                Track <span class="text-red-500">*</span>
                            </label>
                            <select name="track_id" 
                                    id="track_id" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('track_id') border-red-500 @enderror"
                                    required>
                                <option value="">Select a track</option>
                                @foreach($tracks as $track)
                                    <option value="{{ $track->id }}" {{ old('track_id', request('track')) == $track->id ? 'selected' : '' }}>
                                        {{ $track->code }} - {{ $track->description }}
                                    </option>
                                @endforeach
                            </select>
                            @error('track_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Strand Code -->
                        <div>
                            <label for="code" class="block text-sm font-semibold text-gray-900 mb-2">
                                Strand Code <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="code" 
                                   id="code" 
                                   value="{{ old('code') }}"
                                   placeholder="e.g., STEM, ABM, HUMSS"
                                   maxlength="20"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('code') border-red-500 @enderror"
                                   required>
                            @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Unique identifier for the strand (max 20 characters)</p>
                        </div>

                        <!-- Strand Name -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
                                Strand Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name') }}"
                                   placeholder="e.g., Science, Technology, Engineering and Mathematics"
                                   maxlength="100"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">
                                Description (Optional)
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="3"
                                      maxlength="500"
                                      placeholder="Brief description of the strand's focus and career paths..."
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t">
                        <a href="{{ route('admin.strands.index') }}" 
                           class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition text-sm font-medium">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition shadow-sm hover:shadow-md text-sm font-medium">
                            Create Strand
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
