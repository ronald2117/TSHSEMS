@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
            <a href="{{ route('admin.students.index') }}" class="hover:text-green-600">Students</a>
            <span>/</span>
            <span class="text-gray-900">Create New Student</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Create New Student</h1>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm p-6 max-w-3xl">
        <form action="{{ route('admin.students.store') }}" method="POST">
            @csrf

            <!-- Personal Information Section -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- First Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               required>
                        @error('first_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Middle Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Middle Name
                        </label>
                        <input type="text" name="middle_name" value="{{ old('middle_name') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               required>
                        @error('last_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Suffix -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Suffix (Jr., Sr., III, etc.)
                        </label>
                        <input type="text" name="suffix" value="{{ old('suffix') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- Birthdate -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Birthdate
                        </label>
                        <input type="date" name="birthdate" value="{{ old('birthdate') }}" 
                               class="cursor-pointer w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- Gender -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Sex <span class="text-red-500">*</span>
                        </label>
                        <select name="gender" 
                                class="cursor-pointer w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                required>
                            <option value="">Select sex</option>
                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Address
                        </label>
                        <textarea name="address" rows="2" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('address') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Account Information Section -->
            <div class="mb-6 pb-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Account Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- LRN -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            LRN (Learner Reference Number) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="lrn" value="{{ old('lrn') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent font-mono"
                               required>
                        @error('lrn')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" name="password" id="password"
                                   class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   required>
                            <button type="button" onclick="togglePassword('password')" 
                                    class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-600 hover:text-gray-800">
                                <svg id="password-eye-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="password-eye-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   required>
                            <button type="button" onclick="togglePassword('password_confirmation')" 
                                    class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-600 hover:text-gray-800">
                                <svg id="password_confirmation-eye-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="password_confirmation-eye-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Information Section -->
            <div class="mb-6 pb-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Academic Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Strand -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Strand <span class="text-red-500">*</span>
                        </label>
                        <select name="strand_id" 
                                class="cursor-pointer w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                required>
                            <option value="">Select a strand</option>
                            @foreach($strands as $strand)
                                <option value="{{ $strand->id }}" {{ old('strand_id') == $strand->id ? 'selected' : '' }}>
                                    {{ $strand->code }} - {{ $strand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('strand_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Section -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Current Section
                        </label>
                        <select name="current_section_id" 
                                class="cursor-pointer w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">No section assigned</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" {{ old('current_section_id') == $section->id ? 'selected' : '' }}>
                                    Grade {{ $section->grade_level }} - {{ $section->name }} ({{ $section->strand->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('current_section_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Guardian Information Section -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Guardian Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Guardian Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Guardian Name
                        </label>
                        <input type="text" name="guardian_name" value="{{ old('guardian_name') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- Guardian Contact -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Guardian Contact
                        </label>
                        <input type="text" name="guardian_contact" value="{{ old('guardian_contact') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               placeholder="e.g., 09123456789">
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3">
                <button type="submit" 
                        class="cursor-pointer px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition">
                    Create Student
                </button>
                <a href="{{ route('admin.students.index') }}" 
                   class="px-6 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const input = document.getElementById(fieldId);
    const eyeOpen = document.getElementById(fieldId + '-eye-open');
    const eyeClosed = document.getElementById(fieldId + '-eye-closed');
    
    if (input.type === 'password') {
        input.type = 'text';
        eyeOpen.classList.add('hidden');
        eyeClosed.classList.remove('hidden');
    } else {
        input.type = 'password';
        eyeOpen.classList.remove('hidden');
        eyeClosed.classList.add('hidden');
    }
}
</script>
@endsection
