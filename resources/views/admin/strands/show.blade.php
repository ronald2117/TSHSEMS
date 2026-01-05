@extends('layouts.app')
@section('page_title', 'Strand Details')
@section('page_subtitle', 'View strand information and associated sections.')

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
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <!-- Strand Profile Card -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Header Section -->
        <div class="px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <h2 class="text-gray-900 text-2xl font-bold">{{ $strand->code }}</h2>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $strand->track->code }}
                        </span>
                    </div>
                    <p class="text-gray-900 font-medium">{{ $strand->name }}</p>
                    @if($strand->description)
                        <p class="text-gray-600 mt-2">{{ $strand->description }}</p>
                    @endif
                </div>

                <!-- Action Icons -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.strands.edit', $strand) }}" 
                       class="p-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition shadow-sm" 
                       title="Edit Strand">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>

                    <form method="POST" action="{{ route('admin.strands.destroy', $strand) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this strand? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition shadow-sm" 
                                title="Delete Strand">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Track Information -->
        <div class="px-8 py-4 bg-gray-50 border-y border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Associated Track</p>
                    <p class="text-gray-900 font-semibold mt-1">{{ $strand->track->code }} - {{ $strand->track->description }}</p>
                </div>
                <a href="{{ route('admin.tracks.show', $strand->track) }}" 
                   class="text-green-600 hover:text-green-700 text-sm font-medium">
                    View Track →
                </a>
            </div>
        </div>

        <!-- Sections List -->
        <div class="p-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Sections ({{ $strand->sections->count() }})</h3>
                <a href="{{ route('admin.sections.create', ['strand' => $strand->id]) }}" 
                   class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Section
                </a>
            </div>

            @if($strand->sections->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($strand->sections as $section)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">{{ $section->name }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">Grade {{ $section->grade_level }}</p>
                                    <p class="text-xs text-gray-500 mt-2">
                                        <span class="inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                            </svg>
                                            {{ $section->student_profiles_count ?? 0 }} students
                                        </span>
                                    </p>
                                </div>
                                <a href="{{ route('admin.sections.show', $section) }}" 
                                   class="text-green-600 hover:text-green-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <p>No sections associated with this strand yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
