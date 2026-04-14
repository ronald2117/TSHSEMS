@extends('layouts.app')

@section('page_title', 'My Profile')
@section('page_subtitle', 'View and manage your profile information')

@section('toolbar')
    <div class="flex items-center justify-end gap-3 w-full">
        <a href="{{ route('student.profile.edit') }}" class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit Profile
        </a>
    </div>
@endsection

@section('content')
<div class="p-6">
    @if(session('success'))
        <div class="mb-6 p-4 bg-primary-50 border border-primary-200 rounded-lg text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <!-- Profile Card -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Header Section -->
        <div class="px-8 py-6 bg-gradient-to-r from-primary-600 to-primary-700">
            <div class="flex items-center space-x-6">
                @if(auth()->user()->avatar_path && file_exists(public_path('storage/' . auth()->user()->avatar_path)))
                    <img src="{{ asset('storage/' . auth()->user()->avatar_path) }}" alt="{{ auth()->user()->full_name }}" class="w-24 h-24 rounded-full border-4 border-white shadow-lg object-cover">
                @else
                    <div class="w-24 h-24 rounded-full bg-white flex items-center justify-center border-4 border-white shadow-lg">
                        <span class="text-primary-600 text-2xl font-bold">{{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name, 0, 1)) }}</span>
                    </div>
                @endif
                <div class="text-white">
                    <h2 class="text-2xl font-bold">{{ auth()->user()->full_name }}</h2>
                    <p class="text-primary-100 mt-1">LRN: {{ $student->lrn ?? 'N/A' }}</p>
                    @if($student->currentSection)
                        <p class="text-primary-100 text-sm mt-1">{{ $student->currentSection->name }}</p>
                    @endif
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
                        <p class="text-gray-900 mt-1">{{ auth()->user()->first_name }}</p>
                    </div>

                    @if(auth()->user()->middle_name)
                    <div>
                        <label class="text-sm font-medium text-gray-600">Middle Name</label>
                        <p class="text-gray-900 mt-1">{{ auth()->user()->middle_name }}</p>
                    </div>
                    @endif

                    <div>
                        <label class="text-sm font-medium text-gray-600">Last Name</label>
                        <p class="text-gray-900 mt-1">{{ auth()->user()->last_name }}</p>
                    </div>

                    @if(auth()->user()->suffix)
                    <div>
                        <label class="text-sm font-medium text-gray-600">Suffix</label>
                        <p class="text-gray-900 mt-1">{{ auth()->user()->suffix }}</p>
                    </div>
                    @endif

                    @if($student->date_of_birth)
                    <div>
                        <label class="text-sm font-medium text-gray-600">Date of Birth</label>
                        <p class="text-gray-900 mt-1">{{ \Carbon\Carbon::parse($student->date_of_birth)->format('M d, Y') }}</p>
                    </div>
                    @endif

                    @if($student->address)
                    <div>
                        <label class="text-sm font-medium text-gray-600">Address</label>
                        <p class="text-gray-900 mt-1">{{ $student->address }}</p>
                    </div>
                    @endif
                </div>

                <!-- Academic Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Academic Information</h3>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-600">Email Address</label>
                        <p class="text-gray-900 mt-1">{{ auth()->user()->email }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">LRN (Learner Reference Number)</label>
                        <p class="text-gray-900 mt-1 font-mono">{{ $student->lrn ?? 'N/A' }}</p>
                    </div>

                    @if($student->currentSection)
                    <div>
                        <label class="text-sm font-medium text-gray-600">Current Section</label>
                        <p class="text-gray-900 mt-1">
                            Grade {{ $student->currentSection->grade_level ?? '' }} - {{ $student->currentSection->name }}
                            @if($student->currentSection->schoolYear)
                                <span class="text-sm text-gray-500">({{ $student->currentSection->schoolYear->year_start }}-{{ $student->currentSection->schoolYear->year_end }})</span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Strand</label>
                        <p class="text-gray-900 mt-1">{{ $student->currentSection->strand->name ?? 'N/A' }} ({{ $student->currentSection->strand->code ?? '' }})</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Guardian Information -->
            @if($student->guardian_name)
            <div class="space-y-4 md:col-span-2">
                <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Guardian Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Guardian Name</label>
                        <p class="text-gray-900 mt-1">{{ $student->guardian_name }}</p>
                    </div>

                    @if($student->guardian_contact)
                    <div>
                        <label class="text-sm font-medium text-gray-600">Guardian Contact</label>
                        <p class="text-gray-900 mt-1">{{ $student->guardian_contact }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Activity Information -->
            <div class="space-y-4 md:col-span-2">
                <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Activity Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Last Login</label>
                        <p class="text-gray-900 mt-1">
                            @if(auth()->user()->last_login_at)
                                {{ auth()->user()->last_login_at->format('M d, Y h:i A') }}
                                <span class="text-sm text-gray-500">({{ auth()->user()->last_login_at->diffForHumans() }})</span>
                            @else
                                <span class="text-gray-500">Never logged in</span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Member Since</label>
                        <p class="text-gray-900 mt-1">
                            {{ auth()->user()->created_at->format('M d, Y') }}
                            <span class="text-sm text-gray-500">({{ auth()->user()->created_at->diffForHumans() }})</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
