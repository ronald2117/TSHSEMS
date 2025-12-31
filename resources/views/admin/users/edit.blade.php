@extends('layouts.app')

@section('page_title', 'Edit User')
@section('page_subtitle', 'Update user account information.')

@section('content')
<div class="p-6">
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm p-8">
        <form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Avatar Upload -->
            <div class="flex flex-col items-center pb-6 border-b border-gray-100">
                <div class="mb-4">
                    <div id="avatar-preview" class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden">
                        @if($user->avatar_path && file_exists(public_path('storage/' . $user->avatar_path)))
                            <img src="{{ asset('storage/' . $user->avatar_path) }}" class="w-full h-full object-cover" alt="{{ $user->full_name }}">
                        @else
                            <div class="w-full h-full bg-green-600 flex items-center justify-center">
                                <span class="text-white font-semibold text-4xl">{{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <label for="avatar" class="cursor-pointer bg-white px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        Change Avatar
                        <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(event)">
                    </label>
                    @if($user->avatar_path)
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="remove_avatar" value="1" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <span class="text-sm text-gray-600">Remove avatar</span>
                        </label>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-2">JPG, PNG or GIF (Max 2MB)</p>
                @error('avatar')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-6">
                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                        First Name *
                    </label>
                    <input type="text" name="first_name" id="first_name" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                           value="{{ old('first_name', $user->first_name) }}">
                    @error('first_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Last Name *
                    </label>
                    <input type="text" name="last_name" id="last_name" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                           value="{{ old('last_name', $user->last_name) }}">
                    @error('last_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email Address *
                </label>
                <input type="email" name="email" id="email" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                       value="{{ old('email', $user->email) }}">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Login ID -->
            <div>
                <label for="login_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Login ID (Optional)
                </label>
                <input type="text" name="login_id" id="login_id"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                       value="{{ old('login_id', $user->login_id) }}">
                <p class="text-xs text-gray-500 mt-1">e.g., T-2025-001 or ACAD001</p>
                @error('login_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                    Role *
                </label>
                <select name="role" id="role" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Select a role</option>
                    <option value="super_admin" {{ old('role', $user->role) === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="academic_admin" {{ old('role', $user->role) === 'academic_admin' ? 'selected' : '' }}>Academic Admin</option>
                    <option value="registrar_admin" {{ old('role', $user->role) === 'registrar_admin' ? 'selected' : '' }}>Registrar Admin</option>
                    <option value="technical_admin" {{ old('role', $user->role) === 'technical_admin' ? 'selected' : '' }}>Technical Admin</option>
                    <option value="teacher" {{ old('role', $user->role) === 'teacher' ? 'selected' : '' }}>Teacher</option>
                    <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>Student</option>
                </select>
                @error('role')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Account Status *
                </label>
                <select name="status" id="status" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ old('status', $user->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password (Optional for Edit) -->
            <div class="border-t border-gray-100 pt-6">
                <h3 class="text-sm font-medium text-gray-700 mb-4">Change Password (Optional)</h3>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        New Password
                    </label>
                    <input type="password" name="password" id="password"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <p class="text-xs text-gray-500 mt-1">Leave blank to keep current password. Minimum 8 characters if changing.</p>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-700 font-medium">
                    Cancel
                </a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition">
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewAvatar(event) {
    const preview = document.getElementById('avatar-preview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover" alt="Avatar preview">`;
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
