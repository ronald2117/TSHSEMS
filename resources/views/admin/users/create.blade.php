@extends('layouts.app')

@section('page_title', 'Create New User')
@section('page_subtitle', 'Add a new admin, teacher, or staff account.')

@section('content')
<div class="p-6">
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm p-8">
        <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Avatar Upload -->
            <div class="flex flex-col items-center pb-6 border-b border-gray-100">
                <div class="mb-4">
                    <div id="avatar-preview" class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden">
                        <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <label for="avatar" class="cursor-pointer bg-white px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        Choose Avatar
                        <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(event)">
                    </label>
                    <p class="text-xs text-gray-500">JPG, PNG or GIF (Max 2MB)</p>
                </div>
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
                           value="{{ old('first_name') }}">
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
                           value="{{ old('last_name') }}">
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
                       value="{{ old('email') }}">
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
                       value="{{ old('login_id') }}">
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
                        class="cursor-pointer w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Select a role</option>
                    <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="academic_admin" {{ old('role') === 'academic_admin' ? 'selected' : '' }}>Academic Admin</option>
                    <option value="registrar_admin" {{ old('role') === 'registrar_admin' ? 'selected' : '' }}>Registrar Admin</option>
                    <option value="technical_admin" {{ old('role') === 'technical_admin' ? 'selected' : '' }}>Technical Admin</option>
                    <option value="teacher" {{ old('role') === 'teacher' ? 'selected' : '' }}>Teacher</option>
                    <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student</option>
                </select>
                @error('role')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Password *
                </label>
                <div class="relative">
                    <input type="password" name="password" id="password" required
                           class="w-full px-4 py-2 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <button type="button" onclick="togglePassword('password')" class="cursor-pointer absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                        <svg id="password-eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg id="password-eye-off" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                <a href="{{ route('admin.users.index') }}" class="cursor-pointer text-gray-600 hover:text-gray-700 font-medium">
                    Cancel
                </a>
                <button type="submit" class="cursor-pointer bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition">
                    Create User
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

function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const eyeIcon = document.getElementById(inputId + '-eye');
    const eyeOffIcon = document.getElementById(inputId + '-eye-off');
    
    if (input.type === 'password') {
        input.type = 'text';
        eyeIcon.classList.add('hidden');
        eyeOffIcon.classList.remove('hidden');
    } else {
        input.type = 'password';
        eyeIcon.classList.remove('hidden');
        eyeOffIcon.classList.add('hidden');
    }
}
</script>
@endsection
