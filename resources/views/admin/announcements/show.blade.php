@extends('layouts.app')

@section('title', 'View Announcement')

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
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $announcement->title }}</h1>
                <div class="flex items-center gap-3 text-sm text-gray-600">
                    <span>By {{ $announcement->author->first_name }} {{ $announcement->author->last_name }}</span>
                    <span>•</span>
                    <span>{{ $announcement->published_at ? $announcement->published_at->format('F d, Y \a\t g:i A') : 'Not published' }}</span>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.announcements.edit', $announcement) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                    Edit
                </a>
            </div>
        </div>

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
            <p class="text-gray-700 whitespace-pre-wrap">{{ $announcement->content }}</p>
        </div>

        @if ($announcement->expires_at)
            <div class="text-sm text-gray-500 pt-4 border-t">
                <strong>Expires:</strong> {{ $announcement->expires_at->format('F d, Y \a\t g:i A') }}
            </div>
        @endif
    </div>
</div>
@endsection
