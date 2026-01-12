@extends('layouts.app')

@section('title', 'Create Announcement')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <a href="{{ route('admin.announcements.index') }}" class="text-green-600 hover:text-green-700 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Announcements
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Create New Announcement</h1>

        <form action="{{ route('admin.announcements.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                <input type="text" 
                       name="title" 
                       id="title" 
                       value="{{ old('title') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('title') border-red-500 @enderror" 
                       required>
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content *</label>
                <textarea name="content" 
                          id="content" 
                          rows="6" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('content') border-red-500 @enderror" 
                          required>{{ old('content') }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">Publish Date</label>
                    <input type="datetime-local" 
                           name="published_at" 
                           id="published_at" 
                           value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <p class="mt-1 text-xs text-gray-500">Leave blank to publish immediately</p>
                </div>

                <div>
                    <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-2">Expiration Date</label>
                    <input type="datetime-local" 
                           name="expires_at" 
                           id="expires_at" 
                           value="{{ old('expires_at') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <p class="mt-1 text-xs text-gray-500">Optional</p>
                </div>
            </div>

            <div class="mb-4">
                <label for="target_role" class="block text-sm font-medium text-gray-700 mb-2">Target Audience</label>
                <select name="target_role" 
                        id="target_role" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">All Logged-in Users</option>
                    <option value="student" {{ old('target_role') == 'student' ? 'selected' : '' }}>Students Only</option>
                    <option value="teacher" {{ old('target_role') == 'teacher' ? 'selected' : '' }}>Teachers Only</option>
                    <option value="admin" {{ old('target_role') == 'admin' ? 'selected' : '' }}>Admins Only</option>
                </select>
            </div>

            <div class="mb-6 space-y-2">
                <label class="flex items-center">
                    <input type="checkbox" 
                           name="is_public" 
                           value="1" 
                           {{ old('is_public') ? 'checked' : '' }}
                           class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                    <span class="ml-2 text-sm text-gray-700">Show on public landing page</span>
                </label>

                <label class="flex items-center">
                    <input type="checkbox" 
                           name="is_pinned" 
                           value="1" 
                           {{ old('is_pinned') ? 'checked' : '' }}
                           class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                    <span class="ml-2 text-sm text-gray-700">Pin to top</span>
                </label>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                    Create Announcement
                </button>
                <a href="{{ route('admin.announcements.index') }}" class="text-gray-600 hover:text-gray-800">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
