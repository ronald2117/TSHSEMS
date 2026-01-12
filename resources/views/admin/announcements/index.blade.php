@extends('layouts.app')

@section('title', 'Announcements Management')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Announcements Management</h1>
        <a href="{{ route('admin.announcements.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Create Announcement
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Target</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Published</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($announcements as $announcement)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if ($announcement->is_pinned)
                                    <svg class="w-4 h-4 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 3.5L6.5 7H4v2h2.5l-1.5 5.5 5-1.5V16l2-1.5 2 1.5v-3.5l5 1.5L17.5 9H20V7h-2.5L14 3.5z"/>
                                    </svg>
                                @endif
                                <span class="text-sm font-medium text-gray-900">{{ $announcement->title }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($announcement->is_public)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Public</span>
                            @elseif ($announcement->target_role)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                    {{ ucfirst($announcement->target_role) }}
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">All</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($announcement->expires_at && $announcement->expires_at < now())
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Expired</span>
                            @elseif ($announcement->published_at && $announcement->published_at <= now())
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Scheduled</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $announcement->published_at ? $announcement->published_at->format('M d, Y') : 'Not published' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $announcement->author->first_name }} {{ $announcement->author->last_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.announcements.show', $announcement) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                            <a href="{{ route('admin.announcements.edit', $announcement) }}" class="text-green-600 hover:text-green-900 mr-3">Edit</a>
                            <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No announcements found. <a href="{{ route('admin.announcements.create') }}" class="text-green-600 hover:underline">Create one now</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $announcements->links() }}
    </div>
</div>
@endsection
