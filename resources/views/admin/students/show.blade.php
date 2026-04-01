@extends('layouts.app')

@section('page_title', 'Student Details')
@section('page_subtitle', 'View student information')

@section('toolbar')
    <div class="flex items-center justify-end gap-3 w-full">
        <a href="{{ route('admin.students.index') }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Students
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

    <!-- Student Profile Card -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Header Section -->
        <div class="px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    @if($student->avatar_path && file_exists(public_path('storage/' . $student->avatar_path)))
                        <img src="{{ asset('storage/' . $student->avatar_path) }}" alt="{{ $student->full_name }}" class="w-24 h-24 rounded-full border-4 border-white shadow-lg object-cover">
                    @else
                        <div class="w-24 h-24 rounded-full bg-primary-600 flex items-center justify-center border-4 border-white shadow-lg">
                            <span class="text-white text-2xl font-bold">{{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}</span>
                        </div>
                    @endif
                    <div>
                        <h2 class="text-gray-900 text-2xl font-bold">{{ $student->full_name }}</h2>
                        <p class="text-gray-600 mt-1">{{ $student->studentProfile->lrn ?? 'N/A' }}</p>
                        <div class="flex items-center gap-3 mt-2">
                            @if($student->studentProfile && $student->studentProfile->strand)
                                <span class="text-gray-900 px-3 py-1 bg-blue-100 rounded-full text-sm">
                                    {{ $student->studentProfile->strand->code }}
                                </span>
                            @endif
                            <span class="text-white px-3 py-1 {{ $student->is_active ? 'bg-primary-700' : 'bg-red-500' }} rounded-full text-sm">
                                {{ $student->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
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
                        <p class="text-gray-900 mt-1">{{ $student->first_name }}</p>
                    </div>

                    @if($student->middle_name)
                    <div>
                        <label class="text-sm font-medium text-gray-600">Middle Name</label>
                        <p class="text-gray-900 mt-1">{{ $student->middle_name }}</p>
                    </div>
                    @endif

                    <div>
                        <label class="text-sm font-medium text-gray-600">Last Name</label>
                        <p class="text-gray-900 mt-1">{{ $student->last_name }}</p>
                    </div>

                    @if($student->suffix)
                    <div>
                        <label class="text-sm font-medium text-gray-600">Suffix</label>
                        <p class="text-gray-900 mt-1">{{ $student->suffix }}</p>
                    </div>
                    @endif

                    @if($student->studentProfile && $student->studentProfile->birthdate)
                    <div>
                        <label class="text-sm font-medium text-gray-600">Birthdate</label>
                        <p class="text-gray-900 mt-1">{{ \Carbon\Carbon::parse($student->studentProfile->birthdate)->format('M d, Y') }}</p>
                    </div>
                    @endif

                    @if($student->studentProfile && $student->studentProfile->gender)
                    <div>
                        <label class="text-sm font-medium text-gray-600">Sex</label>
                        <p class="text-gray-900 mt-1">{{ $student->studentProfile->gender }}</p>
                    </div>
                    @endif

                    @if($student->studentProfile && $student->studentProfile->address)
                    <div>
                        <label class="text-sm font-medium text-gray-600">Address</label>
                        <p class="text-gray-900 mt-1">{{ $student->studentProfile->address }}</p>
                    </div>
                    @endif
                </div>

                <!-- Academic Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Academic Information</h3>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-600">Email Address</label>
                        <p class="text-gray-900 mt-1">{{ $student->email }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">LRN (Learner Reference Number)</label>
                        <p class="text-gray-900 mt-1 font-mono">{{ $student->studentProfile->lrn ?? 'N/A' }}</p>
                    </div>

                    @if($student->studentProfile && $student->studentProfile->strand)
                    <div>
                        <label class="text-sm font-medium text-gray-600">Strand</label>
                        <p class="text-gray-900 mt-1">{{ $student->studentProfile->strand->name }} ({{ $student->studentProfile->strand->code }})</p>
                    </div>
                    @endif

                    @if($student->studentProfile && $student->studentProfile->currentSection)
                    <div>
                        <label class="text-sm font-medium text-gray-600">Current Section</label>
                        <p class="text-gray-900 mt-1">
                            Grade {{ $student->studentProfile->currentSection->grade_level }} - {{ $student->studentProfile->currentSection->name }}
                            @if($student->studentProfile->currentSection->schoolYear)
                                <span class="text-sm text-gray-500">({{ $student->studentProfile->currentSection->schoolYear->year }})</span>
                            @endif
                        </p>
                    </div>
                    @endif
                </div>

                <!-- Guardian Information -->
                <div class="space-y-4 md:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Guardian Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($student->studentProfile)
                        <div>
                            <label class="text-sm font-medium text-gray-600">Guardian Name</label>
                            <p class="text-gray-900 mt-1">{{ $student->studentProfile->guardian_name ?? 'Not specified' }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Guardian Contact</label>
                            <p class="text-gray-900 mt-1">{{ $student->studentProfile->guardian_contact ?? 'Not specified' }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Activity Information -->
                <div class="space-y-4 md:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Activity Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Last Login</label>
                            <p class="text-gray-900 mt-1">
                                @if($student->last_login_at)
                                    {{ $student->last_login_at->format('M d, Y h:i A') }}
                                    <span class="text-sm text-gray-500">({{ $student->last_login_at->diffForHumans() }})</span>
                                @else
                                    <span class="text-gray-500">Never logged in</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Enrolled Since</label>
                            <p class="text-gray-900 mt-1">
                                {{ $student->created_at->format('M d, Y') }}
                                <span class="text-sm text-gray-500">({{ $student->created_at->diffForHumans() }})</span>
                            </p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Last Updated</label>
                            <p class="text-gray-900 mt-1">
                                {{ $student->updated_at->format('M d, Y h:i A') }}
                                <span class="text-sm text-gray-500">({{ $student->updated_at->diffForHumans() }})</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
