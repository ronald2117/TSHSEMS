@extends('layouts.app')

@section('page_title', 'Edit Profile')
@section('page_subtitle', 'Update your personal and security information')

@section('toolbar')
    <div class="flex items-center justify-end gap-3 w-full">
        <a href="{{ route('admin.profile.index') }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Profile
        </a>
    </div>
@endsection

@section('content')
<div class="p-6 max-w-4xl">
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Edit Profile Form -->
    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm p-6 space-y-6">
        @csrf
        @method('PUT')

        <h2 class="text-lg font-semibold text-gray-900">Profile Photo</h2>
        
        <div class="flex items-center gap-6">
            @if(auth()->user()->avatar_path && file_exists(public_path('storage/' . auth()->user()->avatar_path)))
                <img src="{{ asset('storage/' . auth()->user()->avatar_path) }}" alt="Profile Photo" class="w-24 h-24 rounded-full object-cover border-4 border-gray-200">
            @else
                <div class="w-24 h-24 rounded-full bg-primary-600 flex items-center justify-center border-4 border-gray-200">
                    <span class="text-white text-2xl font-bold">{{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name, 0, 1)) }}</span>
                </div>
            @endif
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Upload New Photo</label>
                <input type="file" name="avatar" accept="image/*" class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-primary-700 hover:file:bg-green-100 cursor-pointer">
                <p class="text-xs text-gray-500 mt-1">JPG, PNG, or GIF (max 2MB)</p>
                @if(auth()->user()->avatar_path)
                    <label class="inline-flex items-center mt-2 cursor-pointer">
                        <input type="checkbox" name="remove_avatar" value="1" class="rounded border-gray-300 text-primary-600 focus:ring-green-500 cursor-pointer">
                        <span class="ml-2 text-sm text-gray-600">Remove current photo</span>
                    </label>
                @endif
            </div>
        </div>

        <div class="pt-6 border-t border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Personal Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name', auth()->user()->first_name) }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                    <input type="text" name="middle_name" value="{{ old('middle_name', auth()->user()->middle_name) }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name', auth()->user()->last_name) }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Suffix</label>
                    <input type="text" name="suffix" value="{{ old('suffix', auth()->user()->suffix) }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="e.g. Jr, III">
                </div>
            </div>
        </div>

        <div class="pt-6 border-t border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Contact Information</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                    <p class="text-xs text-gray-500 mt-1">Used for system notifications and communication</p>
                </div>
            </div>
        </div>

        <div class="flex gap-4 pt-4 border-t border-gray-200">
            <button type="submit" class="cursor-pointer px-6 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-medium transition">
                Save Changes
            </button>
            <a href="{{ route('admin.profile.index') }}" class="cursor-pointer px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition">
                Cancel
            </a>
        </div>
    </form>

    <!-- Change Password Form -->
    <form action="{{ route('admin.profile.password') }}" method="POST" class="bg-white rounded-xl shadow-sm p-6 space-y-6 mt-6">
        @csrf
        @method('PUT')

        <h2 class="text-lg font-semibold text-gray-900">Change Password</h2>
        <p class="text-sm text-gray-600">Update your password to keep your account secure</p>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Password *</label>
                <input type="password" name="current_password" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">New Password *</label>
                <input type="password" name="password" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                    required minlength="8">
                <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password *</label>
                <input type="password" name="password_confirmation" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                    required minlength="8">
            </div>
        </div>

        <button type="submit" class="cursor-pointer px-6 py-2.5 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-medium transition">
            Update Password
        </button>
    </form>
</div>
@endsection
