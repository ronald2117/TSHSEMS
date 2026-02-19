@extends('layouts.app')

@section('page_title', 'My Profile')
@section('page_subtitle', 'View and manage your profile information.')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900">My Profile</h1>
        <p class="text-sm text-gray-600 mt-1">View and manage your profile information</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Profile Information Card -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-green-500 to-primary-600 p-8 text-white">
            <div class="flex items-center gap-6">
                @if(auth()->user()->avatar_path && file_exists(public_path('storage/' . auth()->user()->avatar_path)))
                    <img src="{{ asset('storage/' . auth()->user()->avatar_path) }}" alt="Profile Photo" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">
                @else
                    <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center text-primary-600 text-3xl font-bold shadow-lg">
                        {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h2 class="text-2xl font-bold">{{ auth()->user()->full_name }}</h2>
                    <p class="text-green-50 mt-1">LRN: {{ $student->lrn ?? 'N/A' }}</p>
                    <p class="text-green-50 text-sm">{{ $student->currentSection->name ?? 'No Section Assigned' }}</p>
                </div>
            </div>
        </div>

        <!-- Profile Details -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                    
                    <div>
                        <p class="text-sm text-gray-500">Full Name</p>
                        <p class="font-medium text-gray-900">{{ auth()->user()->full_name }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Email Address</p>
                        <p class="font-medium text-gray-900">{{ auth()->user()->email }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Phone Number</p>
                        <p class="font-medium text-gray-900">{{ $student->phone ?? 'Not provided' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Address</p>
                        <p class="font-medium text-gray-900">{{ $student->address ?? 'Not provided' }}</p>
                    </div>

                    @if($student->date_of_birth)
                    <div>
                        <p class="text-sm text-gray-500">Date of Birth</p>
                        <p class="font-medium text-gray-900">{{ date('F d, Y', strtotime($student->date_of_birth)) }}</p>
                    </div>
                    @endif

                    @if($student->sex)
                    <div>
                        <p class="text-sm text-gray-500">Sex</p>
                        <p class="font-medium text-gray-900 capitalize">{{ $student->sex }}</p>
                    </div>
                    @endif
                </div>

                <!-- Academic Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Academic Information</h3>
                    
                    <div>
                        <p class="text-sm text-gray-500">Grade Level</p>
                        <p class="font-medium text-gray-900">{{ $student->grade_level }}</p>
                    </div>

                    @if($student->currentSection)
                    <div>
                        <p class="text-sm text-gray-500">Section</p>
                        <p class="font-medium text-gray-900">{{ $student->currentSection->name }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Strand</p>
                        <p class="font-medium text-gray-900">{{ $student->currentSection->strand->name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $student->currentSection->strand->description ?? '' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">School Year</p>
                        <p class="font-medium text-gray-900">
                            {{ $student->currentSection->schoolYear->year_start ?? 'N/A' }}-{{ $student->currentSection->schoolYear->year_end ?? 'N/A' }}
                        </p>
                    </div>
                    @endif

                    <div>
                        <p class="text-sm text-gray-500">LRN (Learner Reference Number)</p>
                        <p class="font-medium text-gray-900">{{ $student->lrn ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>

            <!-- Emergency Contact -->
            @if($student->emergency_contact_name)
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Emergency Contact</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Contact Name</p>
                        <p class="font-medium text-gray-900">{{ $student->emergency_contact_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Phone Number</p>
                        <p class="font-medium text-gray-900">{{ $student->emergency_contact_phone ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Relationship</p>
                        <p class="font-medium text-gray-900 capitalize">{{ $student->emergency_contact_relationship ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Guardian Information -->
            @if($student->guardian_name)
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Guardian Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Guardian Name</p>
                        <p class="font-medium text-gray-900">{{ $student->guardian_name }}</p>
                    </div>
                    @if($student->guardian_contact)
                    <div>
                        <p class="text-sm text-gray-500">Guardian Contact</p>
                        <p class="font-medium text-gray-900">{{ $student->guardian_contact }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex gap-4">
                <a href="{{ route('student.profile.edit') }}" class="px-6 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-medium transition">
                    Edit Profile
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
