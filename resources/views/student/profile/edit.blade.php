@extends('layouts.app')

@section('page_title', 'Edit Profile')
@section('page_subtitle', 'Update your personal information and settings.')

@section('content')
<div class="space-y-6 max-w-4xl">
    <!-- Page Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('student.profile.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Profile</h1>
            <p class="text-sm text-gray-600 mt-1">Update your personal information</p>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Edit Profile Form -->
    <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm p-6 space-y-6">
        @csrf
        @method('PUT')

        <h2 class="text-lg font-semibold text-gray-900">Profile Photo</h2>
        
        <div class="flex items-center gap-6">
            <div id="avatar-preview">
                @if(auth()->user()->avatar_path && file_exists(public_path('storage/' . auth()->user()->avatar_path)))
                    <img src="{{ asset('storage/' . auth()->user()->avatar_path) }}" alt="Profile Photo" class="w-24 h-24 rounded-full object-cover border-4 border-gray-200">
                @else
                    <div class="w-24 h-24 rounded-full bg-green-600 flex items-center justify-center border-4 border-gray-200">
                        <span class="text-white text-2xl font-bold">{{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name, 0, 1)) }}</span>
                    </div>
                @endif
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Upload New Photo</label>
                <input type="file" name="avatar" accept="image/*" onchange="previewAvatar(event)" class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                <p class="text-xs text-gray-500 mt-1">JPG, PNG, or GIF (max 2MB)</p>
                @if(auth()->user()->avatar_path)
                    <label class="inline-flex items-center mt-2">
                        <input type="checkbox" name="remove_avatar" value="1" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        <span class="ml-2 text-sm text-gray-600">Remove current photo</span>
                    </label>
                @endif
            </div>
        </div>

        <div class="pt-6 border-t border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Contact Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                <textarea name="address" rows="3" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    placeholder="House No., Street, Barangay, City, Province">{{ old('address', $student->address) }}</textarea>
            </div>
        </div>

        <div class="pt-6 border-t border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Guardian Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Guardian Name</label>
                    <input type="text" name="guardian_name" value="{{ old('guardian_name', $student->guardian_name) }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Guardian Contact Number</label>
                    <input type="text" name="guardian_contact" value="{{ old('guardian_contact', $student->guardian_contact) }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="+63 XXX XXX XXXX">
                </div>
            </div>
        </div>

        <div class="flex gap-4 pt-4">
            <button type="submit" class="px-6 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition">
                Save Changes
            </button>
            <a href="{{ route('student.profile.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition">
                Cancel
            </a>
        </div>
    </form>

    <!-- Change Password Form -->
    <form action="{{ route('student.profile.password') }}" method="POST" class="bg-white rounded-xl shadow-sm p-6 space-y-6">
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

        <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-medium transition">
            Update Password
        </button>
    </form>
</div>

<script>
function previewAvatar(event) {
    const file = event.target.files[0];
    const avatarPreview = document.getElementById('avatar-preview');
    
    if (file && avatarPreview) {
        const reader = new FileReader();
        reader.onload = function(e) {
            avatarPreview.innerHTML = `<img src="${e.target.result}" alt="Profile Photo" class="w-24 h-24 rounded-full object-cover border-4 border-gray-200">`;
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
