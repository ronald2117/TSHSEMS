@extends('layouts.app')

@section('page_title', 'Teacher Details')
@section('page_subtitle', 'View and manage teacher information')

@section('toolbar')
    <div class="flex items-center justify-end gap-3 w-full">
        <a href="{{ route('admin.teachers.index') }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Teachers
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

    <!-- Teacher Profile Card -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Header Section -->
        <div class="px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    @if($teacher->avatar_path && file_exists(public_path('storage/' . $teacher->avatar_path)))
                        <img src="{{ asset('storage/' . $teacher->avatar_path) }}" alt="{{ $teacher->full_name }}" class="w-24 h-24 rounded-full border-4 border-white shadow-lg object-cover">
                    @else
                        <div class="w-24 h-24 rounded-full bg-green-600 flex items-center justify-center border-4 border-white shadow-lg">
                            <span class="text-white text-2xl font-bold">{{ strtoupper(substr($teacher->first_name, 0, 1)) }}{{ strtoupper(substr($teacher->last_name, 0, 1)) }}</span>
                        </div>
                    @endif
                    <div>
                        <h2 class="text-gray-900 text-2xl font-bold">{{ $teacher->full_name }}</h2>
                        <p class="text-gray-600 mt-1">{{ $teacher->login_id ?? 'N/A' }}</p>
                        <div class="flex items-center gap-3 mt-2">
                            @if($teacher->teacherProfile && $teacher->teacherProfile->department)
                                <span class="text-gray-900 px-3 py-1 bg-purple-100 rounded-full text-sm">
                                    {{ $teacher->teacherProfile->department }}
                                </span>
                            @endif
                            <span class="text-white px-3 py-1 {{ $teacher->is_active ? 'bg-green-700' : 'bg-red-500' }} rounded-full text-sm">
                                {{ $teacher->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Action Icons -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.teachers.edit', $teacher) }}" 
                       class="p-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition shadow-sm" 
                       title="Edit Teacher">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>

                    @if(auth()->user()->isSuperAdmin() || auth()->user()->role === 'academic_admin')
                        <form method="POST" action="{{ route('admin.teachers.toggle-status', $teacher) }}" class="inline" onsubmit="return confirm('Are you sure you want to {{ $teacher->is_active ? 'disable' : 'enable' }} this teacher account?')">
                            @csrf
                            <button type="submit" 
                                    class="p-2 {{ $teacher->is_active ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-blue-600 hover:bg-blue-700' }} text-white rounded-lg transition shadow-sm" 
                                    title="{{ $teacher->is_active ? 'Disable' : 'Enable' }} Teacher">
                                @if($teacher->is_active)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @endif
                            </button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('admin.teachers.destroy', $teacher) }}" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete this teacher? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition shadow-sm" 
                                title="Delete Teacher">
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
                <!-- Personal Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Personal Information</h3>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-600">First Name</label>
                        <p class="text-gray-900 mt-1">{{ $teacher->first_name }}</p>
                    </div>

                    @if($teacher->middle_name)
                    <div>
                        <label class="text-sm font-medium text-gray-600">Middle Name</label>
                        <p class="text-gray-900 mt-1">{{ $teacher->middle_name }}</p>
                    </div>
                    @endif

                    <div>
                        <label class="text-sm font-medium text-gray-600">Last Name</label>
                        <p class="text-gray-900 mt-1">{{ $teacher->last_name }}</p>
                    </div>

                    @if($teacher->suffix)
                    <div>
                        <label class="text-sm font-medium text-gray-600">Suffix</label>
                        <p class="text-gray-900 mt-1">{{ $teacher->suffix }}</p>
                    </div>
                    @endif
                </div>

                <!-- Professional Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Professional Information</h3>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-600">Email Address</label>
                        <p class="text-gray-900 mt-1">{{ $teacher->email }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Employee ID</label>
                        <p class="text-gray-900 mt-1 font-mono">{{ $teacher->login_id ?? 'N/A' }}</p>
                    </div>

                    @if($teacher->teacherProfile)
                    <div>
                        <label class="text-sm font-medium text-gray-600">Department</label>
                        <p class="text-gray-900 mt-1">{{ $teacher->teacherProfile->department ?? 'Not specified' }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Specialization</label>
                        <p class="text-gray-900 mt-1">{{ $teacher->teacherProfile->specialization ?? 'Not specified' }}</p>
                    </div>
                    @endif
                </div>

                <!-- Activity Information -->
                <div class="space-y-4 md:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Activity Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Last Login</label>
                            <p class="text-gray-900 mt-1">
                                @if($teacher->last_login_at)
                                    {{ $teacher->last_login_at->format('M d, Y h:i A') }}
                                    <span class="text-sm text-gray-500">({{ $teacher->last_login_at->diffForHumans() }})</span>
                                @else
                                    <span class="text-gray-500">Never logged in</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Hired Since</label>
                            <p class="text-gray-900 mt-1">
                                {{ $teacher->created_at->format('M d, Y') }}
                                <span class="text-sm text-gray-500">({{ $teacher->created_at->diffForHumans() }})</span>
                            </p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Last Updated</label>
                            <p class="text-gray-900 mt-1">
                                {{ $teacher->updated_at->format('M d, Y h:i A') }}
                                <span class="text-sm text-gray-500">({{ $teacher->updated_at->diffForHumans() }})</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advised Sections -->
        @if($teacher->advisedSections && count($teacher->advisedSections) > 0)
        <div class="px-8 py-6 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Advised Sections</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($teacher->advisedSections as $section)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-green-300 hover:shadow-sm transition">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $section->name }}</h4>
                                <p class="text-sm text-gray-600">Grade {{ $section->grade_level }}</p>
                            </div>
                        </div>
                        @if($section->strand)
                        <p class="text-xs text-gray-600 mb-2">{{ $section->strand->name }} ({{ $section->strand->code }})</p>
                        @endif
                        @if($section->schoolYear)
                        <p class="text-xs text-gray-500">{{ $section->schoolYear->year }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        @if((!$teacher->advisedSections || count($teacher->advisedSections) === 0) && (!$teacher->classSchedules || count($teacher->classSchedules) === 0))
        <div class="px-8 py-6 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Teaching Assignments</h3>
            <p class="text-gray-600">No sections or classes assigned yet.</p>
        </div>
        @endif
    </div>
</div>
@endsection
