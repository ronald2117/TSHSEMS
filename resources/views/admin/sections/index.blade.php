@extends('layouts.app')
@section('page_title', 'Sections')
@section('content')
<div class="p-6">
    <div class="flex justify-end items-center mb-6">
        <a href="{{ route('admin.sections.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg transition">
            + New Section
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-primary-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($sections as $section)
        <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        {{ $section->name }}
                        @if($section->is_active)
                            <span class="px-2 py-0.5 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Active</span>
                        @else
                            <span class="px-2 py-0.5 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">Inactive</span>
                        @endif
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Grade {{ $section->grade_level }} - {{ $section->strand->name }}</p>
                </div>
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

            <div class="flex space-x-2 mt-4 border-t pt-4 border-gray-100">
                <form action="{{ route('admin.sections.toggle-status', $section) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-gray-50 text-gray-600 px-3 py-2 rounded-lg text-sm hover:bg-gray-100 transition flex items-center justify-center border border-gray-200" title="{{ $section->is_active ? 'Deactivate' : 'Activate' }}">
                        @if($section->is_active)
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        @else
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        @endif
                    </button>
                </form>
                <a href="{{ route('admin.sections.show', $section) }}" class="flex-1 text-center bg-gray-50 border border-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm hover:bg-gray-100 transition flex items-center justify-center gap-2">
                    Review Details
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
