@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-2">
        <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
            <a href="{{ route('admin.students.index') }}" class="hover:text-green-600">Students</a>
            <span>/</span>
            <span class="text-gray-900">Edit Student</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Student: {{ $student->full_name }}</h1>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm p-6 max-w-3xl">
        <form action="{{ route('admin.students.update', $student) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Personal Information Section -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- First Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="first_name" value="{{ old('first_name', $student->first_name) }}" 
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
                        <input type="text" name="middle_name" value="{{ old('middle_name', $student->middle_name) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="last_name" value="{{ old('last_name', $student->last_name) }}" 
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
                        <input type="text" name="suffix" value="{{ old('suffix', $student->suffix) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- Birthdate -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Birthdate
                        </label>
                        <input type="date" name="birthdate" value="{{ old('birthdate', $student->studentProfile->birthdate) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Address
                        </label>
                        <textarea name="address" rows="2" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('address', $student->studentProfile->address) }}</textarea>
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
                        <input type="text" name="lrn" value="{{ old('lrn', $student->studentProfile->lrn) }}" 
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
                        <input type="email" name="email" value="{{ old('email', $student->email) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            New Password <span class="text-gray-500 text-xs">(leave blank to keep current)</span>
                        </label>
                        <input type="password" name="password" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Confirm New Password
                        </label>
                        <input type="password" name="password_confirmation" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
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
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                required>
                            <option value="">Select a strand</option>
                            @foreach($strands as $strand)
                                <option value="{{ $strand->id }}" {{ old('strand_id', $student->studentProfile->strand_id) == $strand->id ? 'selected' : '' }}>
                                    {{ $strand->name }}
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
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">No section assigned</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" {{ old('current_section_id', $student->studentProfile->current_section_id) == $section->id ? 'selected' : '' }}>
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
                        <input type="text" name="guardian_name" value="{{ old('guardian_name', $student->studentProfile->guardian_name) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- Guardian Contact -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Guardian Contact
                        </label>
                        <input type="text" name="guardian_contact" value="{{ old('guardian_contact', $student->studentProfile->guardian_contact) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               placeholder="e.g., 09123456789">
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3">
                <button type="submit" 
                        class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition">
                    Update Student
                </button>
                <a href="{{ route('admin.students.index') }}" 
                   class="px-6 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
