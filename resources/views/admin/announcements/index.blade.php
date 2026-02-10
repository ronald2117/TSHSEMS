@extends('layouts.app')

@section('page_title', 'Announcements Management')
@section('page_subtitle', 'Create and manage system announcements and public notices')

@section('toolbar')
    <div class="flex items-center justify-between gap-4 w-full">
        <!-- Smart Search -->
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" 
                   id="smart-search" 
                   value="{{ request('search') }}" 
                   placeholder="Search announcements by title or content..." 
                   class="w-full pl-10 pr-10 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
            @if(request('search'))
                <a href="{{ route('admin.announcements.index') }}" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            @endif
        </div>
        
        <!-- Create Announcement Button -->
        @can('create', App\Models\Announcement::class)
        <a href="{{ route('admin.announcements.create') }}" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition shadow-sm hover:shadow-md whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Create Announcement
        </a>
        @endcan
    </div>

    <script>
        // Smart search with debounce
        const searchInput = document.getElementById('smart-search');
        let debounceTimer;
        
        searchInput.addEventListener('input', function(e) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const searchValue = e.target.value;
                const url = new URL(window.location.href);
                
                if (searchValue) {
                    url.searchParams.set('search', searchValue);
                } else {
                    url.searchParams.delete('search');
                }
                
                window.location.href = url.toString();
            }, 500);
        });
    </script>
@endsection

@section('content')
<div class="p-6">
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Title</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Target</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Published</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Author</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
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
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full">Public</span>
                                @elseif ($announcement->target_role)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full">
                                        {{ ucfirst($announcement->target_role) }}
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full">All</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($announcement->expires_at && $announcement->expires_at < now())
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full">Expired</span>
                                @elseif ($announcement->published_at && $announcement->published_at <= now())
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full">Active</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full">Scheduled</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $announcement->published_at ? $announcement->published_at->format('M d, Y') : 'Not published' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $announcement->author->full_name }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    <a href="{{ route('admin.announcements.show', $announcement) }}" 
                                       class="text-gray-600 hover:text-gray-700 transition" 
                                       title="View Details">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-600">
                                @if(request('search'))
                                    No announcements found matching your search.
                                @else
                                    No announcements found.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $announcements->links() }}
    </div>
</div>
@endsection
