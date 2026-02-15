@extends('layouts.app')

@section('page_title', 'Announcement Details')
@section('page_subtitle', 'View announcement information')

@section('toolbar')
    <div class="flex items-center justify-end gap-3 w-full">
        <a href="{{ route('admin.announcements.index') }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Announcements
        </a>
    </div>
@endsection

@section('content')
<div class="p-6">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Header Section -->
        <div class="px-8 py-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-gray-900 text-2xl font-bold mb-2">{{ $announcement->title }}</h2>
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <span>By {{ $announcement->author->full_name }}</span>
                        <span>•</span>
                        <span>{{ $announcement->published_at ? $announcement->published_at->format('F d, Y \a\t g:i A') : 'Not published' }}</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2">
                    @can('update', $announcement)
                    <a href="{{ route('admin.announcements.edit', $announcement) }}" 
                       class="p-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition shadow-sm" 
                       title="Edit Announcement">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>
                    @endcan

                    @can('delete', $announcement)
                    <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="cursor-pointer p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition shadow-sm" 
                                title="Delete Announcement">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>

        <div class="p-8">
            <div class="flex gap-2 mb-6">
            @if ($announcement->is_public)
                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">Public</span>
            @endif
            @if ($announcement->is_pinned)
                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">Pinned</span>
            @endif
            @if ($announcement->target_role)
                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-purple-100 text-purple-800">
                    {{ ucfirst($announcement->target_role) }} Only
                </span>
            @endif
            @if ($announcement->expires_at && $announcement->expires_at < now())
                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">Expired</span>
            @elseif ($announcement->published_at && $announcement->published_at <= now())
                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">Active</span>
            @else
                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">Scheduled</span>
            @endif
            </div>

            <div class="prose max-w-none mb-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 mb-4">Content</h3>
                <p class="text-gray-700 whitespace-pre-wrap">{{ $announcement->content }}</p>
            </div>

            @if ($announcement->expires_at)
                <div class="text-sm text-gray-600 pt-4 border-t">
                    <strong class="font-medium">Expires:</strong> {{ $announcement->expires_at->format('F d, Y \a\t g:i A') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
