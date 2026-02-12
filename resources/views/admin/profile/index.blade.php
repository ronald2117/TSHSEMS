@extends('layouts.app')

@section('page_title', 'My Profile')
@section('page_subtitle', 'View and manage your profile information')

@section('toolbar')
    <div class="flex items-center justify-end gap-3 w-full">
        <a href="{{ route('admin.profile.edit') }}" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition">
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
        <div class="px-8 py-6 bg-gradient-to-r from-green-600 to-green-700">
            <div class="flex items-center space-x-6">
                @if($admin->avatar_path && file_exists(public_path('storage/' . $admin->avatar_path)))
                    <img src="{{ asset('storage/' . $admin->avatar_path) }}" alt="{{ $admin->full_name }}" class="w-24 h-24 rounded-full border-4 border-white shadow-lg object-cover">
                @else
                    <div class="w-24 h-24 rounded-full bg-white flex items-center justify-center border-4 border-white shadow-lg">
                        <span class="text-green-600 text-2xl font-bold">{{ strtoupper(substr($admin->first_name, 0, 1)) }}{{ strtoupper(substr($admin->last_name, 0, 1)) }}</span>
                    </div>
                @endif
                <div class="text-white">
                    <h2 class="text-2xl font-bold">{{ $admin->full_name }}</h2>
                    <p class="text-green-100 mt-1">{{ $admin->login_id }}</p>
                    <p class="text-green-100 text-sm mt-1">
                        @if($admin->role === 'super_admin')
                            Super Administrator
                        @elseif($admin->role === 'academic_admin')
                            Academic Administrator
                        @elseif($admin->role === 'registrar_admin')
                            Registrar Administrator
                        @elseif($admin->role === 'technical_admin')
                            Technical Administrator
                        @endif
                    </p>
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
                        <p class="text-gray-900 mt-1">{{ $admin->first_name }}</p>
                    </div>

                    @if($admin->middle_name)
                    <div>
                        <label class="text-sm font-medium text-gray-600">Middle Name</label>
                        <p class="text-gray-900 mt-1">{{ $admin->middle_name }}</p>
                    </div>
                    @endif

                    <div>
                        <label class="text-sm font-medium text-gray-600">Last Name</label>
                        <p class="text-gray-900 mt-1">{{ $admin->last_name }}</p>
                    </div>

                    @if($admin->suffix)
                    <div>
                        <label class="text-sm font-medium text-gray-600">Suffix</label>
                        <p class="text-gray-900 mt-1">{{ $admin->suffix }}</p>
                    </div>
                    @endif
                </div>

                <!-- Contact & Account Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Contact & Account</h3>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-600">Email Address</label>
                        <p class="text-gray-900 mt-1">{{ $admin->email }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Admin ID</label>
                        <p class="text-gray-900 mt-1">{{ $admin->login_id }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Role</label>
                        <p class="mt-1">
                            <span class="text-white px-3 py-1 bg-green-700 rounded-full text-sm">
                                @if($admin->role === 'super_admin')
                                    Super Admin
                                @elseif($admin->role === 'academic_admin')
                                    Academic Admin
                                @elseif($admin->role === 'registrar_admin')
                                    Registrar Admin
                                @elseif($admin->role === 'technical_admin')
                                    Technical Admin
                                @endif
                            </span>
                        </p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Account Status</label>
                        <p class="mt-1">
                            <span class="text-white px-3 py-1 {{ $admin->is_active ? 'bg-green-700' : 'bg-red-500' }} rounded-full text-sm">
                                {{ $admin->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Account Activity -->
                <div class="space-y-4 md:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Account Activity</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Last Login</label>
                            <p class="text-gray-900 mt-1">
                                @if($admin->last_login_at)
                                    {{ $admin->last_login_at->format('M d, Y h:i A') }}
                                    <span class="text-sm text-gray-500">({{ $admin->last_login_at->diffForHumans() }})</span>
                                @else
                                    <span class="text-gray-500">Never</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Member Since</label>
                            <p class="text-gray-900 mt-1">
                                {{ $admin->created_at->format('M d, Y') }}
                                <span class="text-sm text-gray-500">({{ $admin->created_at->diffForHumans() }})</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
