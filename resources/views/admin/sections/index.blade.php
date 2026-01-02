@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Sections</h1>
        <a href="{{ route('admin.sections.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
            + New Section
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($sections as $section)
        <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">{{ $section->name }}</h3>
                    <p class="text-sm text-gray-600">Grade {{ $section->grade_level }} - {{ $section->strand->name }}</p>
                </div>
                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                    {{ $section->schoolYear->name }}
                </span>
            </div>

            <div class="space-y-2 mb-4">
                <p class="text-sm text-gray-600">
                    <span class="font-medium">School Year:</span> {{ $section->schoolYear->name }}
                </p>
                @if($section->adviser)
                <p class="text-sm text-gray-600">
                    <span class="font-medium">Adviser:</span> {{ $section->adviser->first_name }} {{ $section->adviser->last_name }}
                </p>
                @else
                <p class="text-sm text-gray-500 italic">
                    <span class="font-medium">Adviser:</span> Not assigned
                </p>
                @endif
                <p class="text-sm text-gray-600">
                    <span class="font-medium">Capacity:</span> {{ $section->max_students ?? 'Not set' }}
                </p>
            </div>

            <div class="flex space-x-2">
                <a href="{{ route('admin.sections.show', $section) }}" class="flex-1 text-center bg-gray-50 text-gray-700 px-3 py-2 rounded-lg text-sm hover:bg-gray-100 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    View Details
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 text-gray-500">
            No sections found. Create one to get started.
        </div>
        @endforelse
    </div>

    @if($sections->hasPages())
    <div class="mt-6">
        {{ $sections->links() }}
    </div>
    @endif
</div>
@endsection
