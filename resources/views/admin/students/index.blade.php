@extends('layouts.app')

@section('page_title', 'Student Profile Management')
@section('page_subtitle', 'Manage student profiles, enrollment, and information.')

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
                   placeholder="Search by name, LRN, email, strand, section, or guardian..." 
                   class="w-full pl-10 pr-10 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
            @if(request('search'))
                <a href="{{ route('admin.students.index') }}" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            @endif
        </div>
        
        <!-- Add Student Button (Registrar & Super Admin Only) -->
        @if(auth()->user()->role === 'registrar_admin' || auth()->user()->isSuperAdmin())
        <a href="{{ route('admin.students.create') }}" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition shadow-sm hover:shadow-md whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Student
        </a>
        @endif
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
            }, 500); // 500ms delay
        });
    </script>
@endsection

@section('content')
<div class="p-6">

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Students Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Student Info</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">LRN</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Strand</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Section</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Guardian</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($students as $student)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    @if($student->avatar_path && file_exists(public_path('storage/' . $student->avatar_path)))
                                        <img src="{{ asset('storage/' . $student->avatar_path) }}" alt="{{ $student->full_name }}" class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center">
                                            <span class="text-white text-sm">{{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $student->full_name }}</p>
                                        <p class="text-sm text-gray-600">{{ $student->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-mono text-gray-900">
                                    {{ $student->studentProfile->lrn ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($student->studentProfile && $student->studentProfile->strand)
                                    <span class="text-sm text-gray-900">
                                        {{ $student->studentProfile->strand->code }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($student->studentProfile && $student->studentProfile->currentSection)
                                    <span class="text-sm text-gray-900">
                                        Grade {{ $student->studentProfile->currentSection->grade_level }} - {{ $student->studentProfile->currentSection->name }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">Not assigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($student->studentProfile)
                                    <div class="text-sm text-gray-900">
                                        {{ $student->studentProfile->guardian_name ?? 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $student->studentProfile->guardian_contact ?? '' }}
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    <a href="{{ route('admin.students.show', $student) }}" 
                                       class="text-gray-600 hover:text-gray-700 transition mb-1" 
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
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    <p class="text-lg font-medium">No students found</p>
                                    <p class="text-sm mt-1">Get started by creating a new student profile.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($students->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $students->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
