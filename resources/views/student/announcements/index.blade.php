@extends('layouts.app')

@section('page_title', 'Announcements')
@section('page_subtitle', 'Stay updated with important notices and information.')

@section('content')
<div class="p-5 space-y-6">
    <!-- Announcements List -->
    <div class="space-y-4">
        @forelse($announcements as $announcement)
            <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
                <!-- Announcement Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            @if($announcement->is_pinned)
                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L11 4.323V3a1 1 0 011-1zm-5 8.274l-.818 2.552c-.25.78.107 1.648.823 2.134C5.51 15.556 6.146 16 7 16s1.49-.444 1.995-1.04c.716-.486 1.073-1.354.823-2.134L9 10.274V6a1 1 0 00-2 0v4.274z"/>
                                </svg>
                            @endif
                            <h2 class="text-lg font-semibold text-gray-900">{{ $announcement->title }}</h2>
                        </div>
                        <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                {{ $announcement->published_at->format('M d, Y') }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                {{ $announcement->author->name }}
                            </span>
                        </div>
                    </div>
                    
                    @if($announcement->priority === 'high')
                        <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">
                            High Priority
                        </span>
                    @elseif($announcement->priority === 'medium')
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">
                            Medium Priority
                        </span>
                    @endif
                </div>

                <!-- Announcement Content -->
                <div class="prose prose-sm max-w-none text-gray-700">
                    {{ Str::limit($announcement->content, 300) }}
                </div>

                @if(strlen($announcement->content) > 300)
                    <button onclick="showAnnouncement({{ $announcement->id }})" class="mt-3 text-primary-600 hover:text-primary-700 text-sm font-medium">
                        Read more →
                    </button>
                @endif

                <!-- Attachments -->
                @if($announcement->attachments)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500 mb-2">Attachments:</p>
                        <div class="flex gap-2 flex-wrap">
                            @foreach(json_decode($announcement->attachments) as $attachment)
                                <a href="{{ Storage::url($attachment) }}" target="_blank" class="flex items-center gap-1 px-3 py-1 bg-gray-50 hover:bg-gray-100 rounded-lg text-xs text-gray-700">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ basename($attachment) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No announcements yet</h3>
                <p class="text-sm text-gray-500">Check back later for updates</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($announcements->hasPages())
        <div class="mt-6">
            {{ $announcements->links() }}
        </div>
    @endif
</div>

<!-- Modal for Full Announcement -->
<div id="announcementModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex justify-between items-start">
            <h3 id="modalTitle" class="text-xl font-bold text-gray-900"></h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
            <div id="modalContent" class="prose prose-sm max-w-none"></div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const announcements = @json($announcements->items());
    
    function showAnnouncement(id) {
        const announcement = announcements.find(a => a.id === id);
        if (announcement) {
            document.getElementById('modalTitle').textContent = announcement.title;
            document.getElementById('modalContent').innerHTML = announcement.content;
            document.getElementById('announcementModal').classList.remove('hidden');
        }
    }
    
    function closeModal() {
        document.getElementById('announcementModal').classList.add('hidden');
    }
    
    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });
    
    // Close modal on background click
    document.getElementById('announcementModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
</script>
@endpush
@endsection
