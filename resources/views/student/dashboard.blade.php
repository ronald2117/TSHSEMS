@extends('layouts.app')

@section('page_title', 'Student Dashboard')
@section('page_subtitle', 'Welcome back, ' . auth()->user()->first_name . '! Here\'s your academic overview.')

@section('content')
<div class="p-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">General Weighted Average</p>
                    <p class="text-3xl font-bold text-blue-600">{{ number_format($averageGrade, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Subjects</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalGrades }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Section</p>
                    <p class="text-xl font-bold text-gray-900">{{ $profile->currentSection->name ?? 'N/A' }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Strand</p>
                    <p class="text-xl font-bold text-gray-900">{{ $profile->strand->code ?? 'N/A' }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Recent Grades (2/3 width) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Grades</h2>
                    <a href="{{ route('student.grades.index') }}" class="text-green-600 hover:text-green-700 font-medium text-sm">View All</a>
                </div>
                <div class="p-6">
                    @if($recentGrades->isEmpty())
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-1">No grades available</h3>
                            <p class="text-sm text-gray-500">Your grades will appear here once they are released by your teachers</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($recentGrades as $grade)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900">{{ $grade->classSchedule->subject->name }}</h3>
                                        <p class="text-sm text-gray-600">Quarter {{ $grade->quarter }}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="inline-flex items-center justify-center w-14 h-14 bg-green-100 rounded-lg">
                                            <span class="text-xl font-bold text-green-600">{{ number_format($grade->final_grade, 0) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="pt-3 border-t border-gray-100">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-500">Status:</span>
                                        @if($grade->status === 'approved')
                                            <span class="text-green-600 text-xs font-medium">Approved</span>
                                        @elseif($grade->status === 'submitted')
                                            <span class="text-yellow-600 text-xs font-medium">Pending</span>
                                        @else
                                            <span class="text-gray-600 text-xs font-medium">Draft</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Announcements (1/3 width) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 sticky top-6">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                        </svg>
                        Announcements
                    </h2>
                </div>
                <div class="p-6">
                    @if(isset($announcements) && $announcements->count() > 0)
                        <div class="space-y-4">
                            @foreach($announcements as $announcement)
                            <div class="border-l-4 border-green-500 pl-4 py-2">
                                <h3 class="font-semibold text-gray-900 text-sm mb-1">{{ $announcement->title }}</h3>
                                <p class="text-xs text-gray-600 mb-2">{{ Str::limit(strip_tags($announcement->content), 100) }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500">{{ $announcement->published_at->diffForHumans() }}</span>
                                    <button onclick="showAnnouncementModal({{ $announcement->id }})" class="text-xs text-green-600 hover:text-green-700 font-medium">See more →</button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-gray-500 text-sm">No announcements yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Announcement Modal -->
<div id="announcementModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-xl bg-white">
        <div class="flex items-center justify-between pb-3 border-b border-gray-200">
            <h3 id="modalTitle" class="text-xl font-semibold text-gray-900"></h3>
            <button onclick="closeAnnouncementModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="mt-4">
            <div class="flex items-center text-sm text-gray-500 mb-4">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span id="modalDate"></span>
            </div>
            <div id="modalContent" class="text-gray-700 leading-relaxed whitespace-pre-wrap"></div>
        </div>
        <div class="mt-6 flex justify-end">
            <button onclick="closeAnnouncementModal()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                Close
            </button>
        </div>
    </div>
</div>

<script>
@if(isset($announcements))
const announcements = @json($announcements);

function showAnnouncementModal(announcementId) {
    const announcement = announcements.find(a => a.id === announcementId);
    if (announcement) {
        document.getElementById('modalTitle').textContent = announcement.title;
        document.getElementById('modalContent').textContent = announcement.content;
        document.getElementById('modalDate').textContent = new Date(announcement.published_at).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        document.getElementById('announcementModal').classList.remove('hidden');
    }
}

function closeAnnouncementModal() {
    document.getElementById('announcementModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('announcementModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeAnnouncementModal();
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAnnouncementModal();
    }
});
@endif
</script>
@endsection
