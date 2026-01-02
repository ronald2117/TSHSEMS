@extends('layouts.app')

@section('page_title', 'User Details')
@section('page_subtitle', 'View and manage user information')

@section('toolbar')
    <div class="flex items-center justify-end gap-3 w-full">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Users
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

    <!-- User Profile Card -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Header Section -->
        <div class="px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                @if($user->avatar_path && file_exists(public_path('storage/' . $user->avatar_path)))
                    <img src="{{ asset('storage/' . $user->avatar_path) }}" alt="{{ $user->full_name }}" class="w-24 h-24 rounded-full border-4 border-white shadow-lg object-cover">
                @else
                    <div class="w-24 h-24 rounded-full bg-green-600 flex items-center justify-center border-4 border-white shadow-lg">
                        <span class="text-white text-2xl font-bold">{{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}</span>
                    </div>
                @endif
                <div>
                    <h2 class="text-gray-900 text-2xl font-bold">{{ $user->full_name }}</h2>
                    <p class="text-gray-900 mt-1">{{ $user->login_id }}</p>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="text-gray-900 px-3 py-1 bg-white/20 rounded-full text-sm backdrop-blur-sm">
                            {{ str_replace('_', ' ', ucwords($user->role)) }}
                        </span>
                        <span class="text-white px-3 py-1 {{ $user->is_active ? 'bg-green-700' : 'bg-red-500' }} rounded-full text-sm">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Action Icons -->
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.users.edit', $user) }}" 
                   class="p-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition shadow-sm" 
                   title="Edit User">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>

                <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="p-2 text-white rounded-lg transition shadow-sm {{ $user->is_active ? 'bg-orange-500 hover:bg-orange-600' : 'bg-green-600 hover:bg-green-700' }}" 
                            title="{{ $user->is_active ? 'Disable Account' : 'Enable Account' }}">
                        @if($user->is_active)
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

                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete this user? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition shadow-sm" 
                            title="Delete User">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
            </div>
            </div>
        </div>
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Personal Information</h3>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-600">First Name</label>
                        <p class="text-gray-900 mt-1">{{ $user->first_name }}</p>
                    </div>

                    @if($user->middle_name)
                    <div>
                        <label class="text-sm font-medium text-gray-600">Middle Name</label>
                        <p class="text-gray-900 mt-1">{{ $user->middle_name }}</p>
                    </div>
                    @endif

                    <div>
                        <label class="text-sm font-medium text-gray-600">Last Name</label>
                        <p class="text-gray-900 mt-1">{{ $user->last_name }}</p>
                    </div>

                    @if($user->suffix)
                    <div>
                        <label class="text-sm font-medium text-gray-600">Suffix</label>
                        <p class="text-gray-900 mt-1">{{ $user->suffix }}</p>
                    </div>
                    @endif
                </div>

                <!-- Account Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Account Information</h3>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-600">Email Address</label>
                        <p class="text-gray-900 mt-1">{{ $user->email }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Login ID</label>
                        <p class="text-gray-900 mt-1">{{ $user->login_id }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Role</label>
                        <p class="text-gray-900 mt-1">{{ str_replace('_', ' ', ucwords($user->role)) }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Account Status</label>
                        <p class="text-gray-900 mt-1">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Activity Information -->
                <div class="space-y-4 md:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Activity Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Last Login</label>
                            <p class="text-gray-900 mt-1">
                                @if($user->last_login_at)
                                    {{ $user->last_login_at->format('M d, Y h:i A') }}
                                    <span class="text-sm text-gray-500">({{ $user->last_login_at->diffForHumans() }})</span>
                                @else
                                    <span class="text-gray-500">Never logged in</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Account Created</label>
                            <p class="text-gray-900 mt-1">
                                {{ $user->created_at->format('M d, Y h:i A') }}
                                <span class="text-sm text-gray-500">({{ $user->created_at->diffForHumans() }})</span>
                            </p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Last Updated</label>
                            <p class="text-gray-900 mt-1">
                                {{ $user->updated_at->format('M d, Y h:i A') }}
                                <span class="text-sm text-gray-500">({{ $user->updated_at->diffForHumans() }})</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
