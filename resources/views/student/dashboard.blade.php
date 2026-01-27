@extends('layouts.app')

@section('page_title', 'Student Dashboard')
@section('page_subtitle', 'Welcome back, ' . auth()->user()->first_name . '! Here\'s your academic overview.')

@section('content')
<div class="p-6">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-sm p-6 mb-6 text-white">
        <h2 class="text-2xl font-bold mb-2">Welcome, {{ auth()->user()->first_name }}!</h2>
        <p class="text-blue-100">{{ $profile->currentSection->name ?? 'No Section Assigned' }} • {{ $profile->strand->name ?? 'N/A' }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Grades</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalGrades }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">GWA</p>
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
                            <p class="text-gray-600 text-sm">Attendance</p>
                            <p class="text-3xl font-bold text-green-600">95%</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Grades -->
            <div class="bg-white rounded-xl shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Grades</h2>
                    <a href="{{ route('student.grades.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                        View All →
                    </a>
                </div>
                <div class="p-6">
                    @if($recentGrades->isEmpty())
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <p class="text-gray-600">No grades available yet</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($recentGrades as $grade)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">{{ $grade->classSchedule->subject->name }}</p>
                                    <p class="text-sm text-gray-600">Quarter {{ $grade->quarter }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-block bg-blue-100 text-blue-800 px-4 py-2 rounded-lg text-lg font-bold">
                                        {{ $grade->final_grade }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Links -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Access</h3>
                <div class="space-y-2">
                    <a href="{{ route('student.grades.index') }}" class="flex items-center gap-3 p-3 hover:bg-blue-50 rounded-lg transition text-gray-700 hover:text-blue-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span class="font-medium">View All Grades</span>
                    </a>
                    <a href="{{ route('student.schedule.index') }}" class="flex items-center gap-3 p-3 hover:bg-blue-50 rounded-lg transition text-gray-700 hover:text-blue-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="font-medium">My Schedule</span>
                    </a>
                    <a href="{{ route('student.documents.index') }}" class="flex items-center gap-3 p-3 hover:bg-blue-50 rounded-lg transition text-gray-700 hover:text-blue-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="font-medium">Request Documents</span>
                    </a>
                </div>
            </div>

            <!-- Announcements -->
            <div class="bg-white rounded-xl shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Announcements</h3>
                </div>
                <div class="p-6">
                    @if(isset($announcements) && $announcements->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($announcements->take(3) as $announcement)
                            <div class="border-l-4 border-blue-500 pl-4 py-2">
                                <h4 class="font-medium text-gray-900 mb-1">{{ $announcement->title }}</h4>
                                <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit(strip_tags($announcement->content), 80) }}</p>
                                <button onclick="showAnnouncementModal('{{ $announcement->id }}')" class="text-blue-600 hover:text-blue-700 text-sm mt-2">
                                    Read more →
                                </button>
                            </div>
                            @endforeach
                        </div>
                        <a href="{{ route('student.announcements.index') }}" class="block text-center mt-4 text-blue-600 hover:text-blue-700 text-sm font-medium">
                            View All Announcements
                        </a>
                    @else
                        <p class="text-gray-500 text-sm text-center py-4">No announcements</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Announcement Modal -->
<div id="announcementModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <h3 id="modalTitle" class="text-xl font-semibold text-gray-900"></h3>
            <button onclick="closeAnnouncementModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="p-6">
            <div id="modalContent" class="prose max-w-none"></div>
            <div id="modalDate" class="text-sm text-gray-500 mt-4"></div>
        </div>
    </div>
</div>

<script>
@if(isset($announcements))
const announcements = {!! json_encode($announcements->map(fn($a) => [
    'id' => $a->id,
    'title' => $a->title,
    'content' => $a->content,
    'date' => $a->created_at->format('M d, Y')
])) !!};

function showAnnouncementModal(id) {
    const announcement = announcements.find(a => a.id == id);
    if (announcement) {
        document.getElementById('modalTitle').textContent = announcement.title;
        document.getElementById('modalContent').innerHTML = announcement.content;
        document.getElementById('modalDate').textContent = 'Posted on ' + announcement.date;
        document.getElementById('announcementModal').classList.remove('hidden');
    }
}

function closeAnnouncementModal() {
    document.getElementById('announcementModal').classList.add('hidden');
}
@endif
</script>
@endsection
