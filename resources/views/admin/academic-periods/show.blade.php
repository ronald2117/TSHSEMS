@extends('layouts.app')

@section('page_title', 'Academic Period Details')
@section('page_subtitle', 'View and manage academic period information')

@section('toolbar')
    <div class="flex items-center justify-end gap-3 w-full">
        <a href="{{ route('admin.academic-periods.index') }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Academic Periods
        </a>
    </div>
@endsection

@section('content')
<div class="p-6">
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <!-- Academic Period Profile Card -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Header Section -->
        <div class="px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div>
                        <h2 class="text-gray-900 text-2xl font-bold">{{ $academicPeriod->name }}</h2>
                        <p class="text-gray-600 mt-1">{{ $academicPeriod->schoolYear->name ?? 'N/A' }}</p>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="text-white px-3 py-1 {{ $academicPeriod->status === 'Active' ? 'bg-green-700' : 'bg-gray-500' }} rounded-full text-sm">
                                {{ $academicPeriod->status }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Action Icons -->
                <div class="flex items-center gap-2">
                    @if($academicPeriod->status !== 'Active')
                    <form method="POST" action="{{ route('admin.academic-periods.toggle-status', $academicPeriod) }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="p-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition shadow-sm" 
                                title="Activate Period">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                    </form>
                    @else
                    <form method="POST" action="{{ route('admin.academic-periods.toggle-status', $academicPeriod) }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="p-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition shadow-sm" 
                                title="Close Period">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('admin.academic-periods.edit', $academicPeriod) }}" 
                       class="p-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition shadow-sm" 
                       title="Edit Academic Period">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>

                    <form method="POST" action="{{ route('admin.academic-periods.destroy', $academicPeriod) }}" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete this academic period? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition shadow-sm" 
                                title="Delete Academic Period">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Academic Period Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Period Information</h3>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-600">Period Name</label>
                        <p class="text-gray-900 mt-1">{{ $academicPeriod->name }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">School Year</label>
                        <p class="text-gray-900 mt-1">{{ $academicPeriod->schoolYear->name ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="text-xs font-semibold {{ $academicPeriod->status === 'Active' ? 'text-primary-600' : 'text-gray-600' }}">
                                {{ $academicPeriod->status }}
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Statistics</h3>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Associated Sections</label>
                        <p class="text-gray-900 mt-1">{{ $academicPeriod->sections->count() }} section{{ $academicPeriod->sections->count() != 1 ? 's' : '' }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Class Schedules</label>
                        <p class="text-gray-900 mt-1">{{ $academicPeriod->classSchedules->count() ?? 0 }} schedule{{ ($academicPeriod->classSchedules->count() ?? 0) != 1 ? 's' : '' }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Created</label>
                        <p class="text-gray-900 mt-1">
                            {{ $academicPeriod->created_at->format('M d, Y') }}
                            <span class="text-sm text-gray-500">({{ $academicPeriod->created_at->diffForHumans() }})</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Associated Sections -->
        @if($academicPeriod->sections && $academicPeriod->sections->count() > 0)
        <div class="px-8 py-6 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Associated Sections ({{ $academicPeriod->sections->count() }})</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Section Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade Level</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Strand</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Students</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($academicPeriod->sections as $section)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $section->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    Grade {{ $section->grade_level }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $section->strand->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-center">
                                    {{ $section->students_count ?? 0 }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="px-8 py-6 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Associated Sections</h3>
            <p class="text-gray-600">No sections linked to this academic period yet.</p>
        </div>
        @endif
    </div>
</div>
@endsection
