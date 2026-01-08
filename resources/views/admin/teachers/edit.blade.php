@extends('layouts.app')

@section('page_title', 'Edit Teacher')
@section('page_subtitle', 'Update teacher profile and professional information.')

@section('toolbar')
    <div class="flex items-center justify-end gap-3 w-full">
        <a href="{{ route('admin.teachers.show', $teacher) }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Teacher Details
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
        <form method="POST" action="{{ route('admin.teachers.update', $teacher) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Header Section -->
            <div class="px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div id="avatar-preview" class="relative">
                            @if($teacher->avatar_path && file_exists(public_path('storage/' . $teacher->avatar_path)))
                                <img src="{{ asset('storage/' . $teacher->avatar_path) }}" alt="{{ $teacher->full_name }}" class="w-24 h-24 rounded-full border-4 border-white shadow-lg object-cover">
                            @else
                                <div class="w-24 h-24 rounded-full bg-green-600 flex items-center justify-center border-4 border-white shadow-lg">
                                    <span class="text-white text-2xl font-bold">{{ strtoupper(substr($teacher->first_name, 0, 1)) }}{{ strtoupper(substr($teacher->last_name, 0, 1)) }}</span>
                                </div>
                            @endif
                            <label for="avatar" class="absolute bottom-0 right-0 w-8 h-8 bg-green-600 hover:bg-green-700 rounded-full flex items-center justify-center cursor-pointer shadow-lg transition">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(event)">
                            </label>
                        </div>
                        <div>
                            <h2 class="text-gray-900 text-2xl font-bold">{{ $teacher->full_name }}</h2>
                            <p class="text-gray-900 mt-1">{{ $teacher->login_id }}</p>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="text-gray-900 px-3 py-1 bg-white/20 rounded-full text-sm backdrop-blur-sm">
                                    {{ str_replace('_', ' ', ucwords($teacher->role)) }}
                                </span>
                                <span class="text-white px-3 py-1 {{ $teacher->is_active ? 'bg-green-700' : 'bg-red-500' }} rounded-full text-sm">
                                    {{ $teacher->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @if($teacher->avatar_path)
                    <div class="mt-4 flex items-center space-x-2">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="remove_avatar" value="1" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <span class="text-sm text-gray-600">Remove avatar</span>
                        </label>
                    </div>
                @endif
                @error('avatar')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="p-8 space-y-6">
                <!-- Personal Information Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 mb-4">Personal Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- First Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="first_name" value="{{ old('first_name', $teacher->first_name) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                   required>
                            @error('first_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Middle Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Middle Name
                            </label>
                            <input type="text" name="middle_name" value="{{ old('middle_name', $teacher->middle_name) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="last_name" value="{{ old('last_name', $teacher->last_name) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                   required>
                            @error('last_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Suffix -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Suffix
                            </label>
                            <input type="text" name="suffix" value="{{ old('suffix', $teacher->suffix) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                   placeholder="Jr., Sr., III, etc.">
                        </div>
                    </div>
                </div>

                <!-- Account Information Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 mb-4">Account Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Employee ID -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Employee ID <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="employee_id" value="{{ old('employee_id', $teacher->login_id) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 font-mono"
                                   placeholder="e.g., T-2025-001"
                                   required>
                            @error('employee_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', $teacher->email) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                   required>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                New Password
                            </label>
                            <input type="password" name="password" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                   placeholder="Leave blank to keep current">
                            <p class="text-xs text-gray-500 mt-1">Minimum 8 characters if changing</p>
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Confirm Password
                            </label>
                            <input type="password" name="password_confirmation" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                    </div>
                </div>

                <!-- Professional Information Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 mb-4">Professional Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Department -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Department
                            </label>
                            <input type="text" name="department" value="{{ old('department', $teacher->teacherProfile->department) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                   placeholder="e.g., Science Department, Mathematics">
                            @error('department')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Specialization -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Specialization
                            </label>
                            <input type="text" name="specialization" value="{{ old('specialization', $teacher->teacherProfile->specialization) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                   placeholder="e.g., Biology, Calculus, English Literature">
                            @error('specialization')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                    <a href="{{ route('admin.teachers.show', $teacher) }}" 
                       class="text-gray-600 hover:text-gray-700 font-medium px-4 py-2">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg font-medium transition shadow-sm hover:shadow-md">
                        Update Teacher
                    </button>
                </div>
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
