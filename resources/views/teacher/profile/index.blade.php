@extends('layouts.app')

@section('page_title', 'My Profile')
@section('page_subtitle', 'View and manage your profile information')

@section('toolbar')
    <div class="flex items-center justify-end gap-3 w-full">
        <a href="{{ route('teacher.profile.edit') }}" class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition">
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
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <!-- Profile Card -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Header Section -->
        <div class="px-8 py-6 bg-gradient-to-r from-primary-600 to-primary-700">
            <div class="flex items-center space-x-6">
                @if($teacher->avatar_path && file_exists(public_path('storage/' . $teacher->avatar_path)))
                    <img src="{{ asset('storage/' . $teacher->avatar_path) }}" alt="{{ $teacher->full_name }}" class="w-24 h-24 rounded-full border-4 border-white shadow-lg object-cover">
                @else
                    <div class="w-24 h-24 rounded-full bg-white flex items-center justify-center border-4 border-white shadow-lg">
                        <span class="text-primary-600 text-2xl font-bold">{{ strtoupper(substr($teacher->first_name, 0, 1)) }}{{ strtoupper(substr($teacher->last_name, 0, 1)) }}</span>
                    </div>
                @endif
                <div class="text-white">
                    <h2 class="text-2xl font-bold">{{ $teacher->full_name }}</h2>
                    <p class="text-green-100 mt-1">{{ $teacher->login_id }}</p>
                    @if($teacher->teacherProfile && $teacher->teacherProfile->department)
                        <p class="text-green-100 text-sm mt-1">{{ $teacher->teacherProfile->department }}</p>
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

                <!-- Contact & Account Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Contact & Account</h3>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-600">Email Address</label>
                        <p class="text-gray-900 mt-1">{{ $teacher->email }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Employee ID</label>
                        <p class="text-gray-900 mt-1">{{ $teacher->login_id }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Account Status</label>
                        <p class="mt-1">
                            <span class="text-white px-3 py-1 {{ $teacher->is_active ? 'bg-primary-700' : 'bg-red-500' }} rounded-full text-sm">
                                {{ $teacher->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Professional Information -->
                @if($teacher->teacherProfile)
                <div class="space-y-4 md:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Professional Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($teacher->teacherProfile->department)
                        <div>
                            <label class="text-sm font-medium text-gray-600">Department</label>
                            <p class="text-gray-900 mt-1">{{ $teacher->teacherProfile->department }}</p>
                            <p class="text-xs text-gray-500 mt-1">Managed by Academic Admin</p>
                        </div>
                        @endif

                        @if($teacher->teacherProfile->specialization)
                        <div>
                            <label class="text-sm font-medium text-gray-600">Specialization</label>
                            <p class="text-gray-900 mt-1">{{ $teacher->teacherProfile->specialization }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Account Activity -->
                <div class="space-y-4 md:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Account Activity</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Last Login</label>
                            <p class="text-gray-900 mt-1">
                                @if($teacher->last_login_at)
                                    {{ $teacher->last_login_at->format('M d, Y h:i A') }}
                                    <span class="text-sm text-gray-500">({{ $teacher->last_login_at->diffForHumans() }})</span>
                                @else
                                    <span class="text-gray-500">Never</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Member Since</label>
                            <p class="text-gray-900 mt-1">
                                {{ $teacher->created_at->format('M d, Y') }}
                                <span class="text-sm text-gray-500">({{ $teacher->created_at->diffForHumans() }})</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Information Notice -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="ml-3 text-sm text-blue-800">
                <p class="font-medium">Profile Management Information</p>
                <ul class="mt-2 space-y-1 list-disc list-inside">
                    <li>You can update your email, specialization, profile photo, and password</li>
                    <li>Official information (name, employee ID, department) is managed by Academic Admin</li>
                    <li>For password recovery assistance, contact Technical Admin</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
