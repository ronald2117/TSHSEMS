@extends('layouts.app')
@section('page_title', 'Transfer Student')
@section('page_subtitle', 'Transfer student to a different section')

@section('content')
<div class="p-6">
    <div class="max-w-2xl mx-auto">
        <!-- Student Information Card -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Student Information</h2>
            </div>
            <div class="p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                        <span class="text-2xl font-bold text-green-600">
                            {{ substr($student->user->first_name ?? 'S', 0, 1) }}{{ substr($student->user->last_name ?? '', 0, 1) }}
                        </span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $student->user->full_name }}</h3>
                        <p class="text-sm text-gray-600">LRN: {{ $student->lrn }}</p>
                        <p class="text-sm text-gray-600">
                            Current Section: 
                            <span class="font-medium text-green-600">
                                {{ $student->currentSection->name }} ({{ $student->currentSection->strand->name ?? '' }})
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transfer Form -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Transfer Details</h2>
                <p class="mt-1 text-sm text-gray-600">Select the new section for this student.</p>
            </div>

            @if($errors->any())
                <div class="p-4 bg-red-50 border-b border-red-200">
                    <div class="flex">
                        <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ $errors->first() }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.enrollment.transfer.process', $student->id) }}" method="POST" class="p-6 space-y-6">
                @csrf

                <!-- New Section Selection -->
                <div>
                    <label for="new_section_id" class="block text-sm font-medium text-gray-700 mb-2">
                        New Section <span class="text-red-500">*</span>
                    </label>
                    <select name="new_section_id" id="new_section_id" required
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('new_section_id') border-red-500 @enderror">
                        <option value="">Select a Section</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ old('new_section_id') == $section->id ? 'selected' : '' }}>
                                Grade {{ $section->grade_level }} - {{ $section->name }} 
                                ({{ $section->strand->name ?? 'No Strand' }})
                                - {{ $section->schoolYear->name ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('new_section_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    @if($sections->isEmpty())
                        <p class="mt-2 text-sm text-yellow-600">
                            <svg class="inline w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            No available sections for transfer. The student's current section is excluded from the list.
                        </p>
                    @endif
                </div>

                <!-- Transfer Date -->
                <div>
                    <label for="transfer_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Transfer Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="transfer_date" id="transfer_date" required
                           value="{{ old('transfer_date', date('Y-m-d')) }}"
                           class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('transfer_date') border-red-500 @enderror">
                    @error('transfer_date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Reason -->
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Reason for Transfer (Optional)
                    </label>
                    <textarea name="reason" id="reason" rows="3"
                              placeholder="Enter the reason for transferring this student..."
                              class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('reason') border-red-500 @enderror">{{ old('reason') }}</textarea>
                    @error('reason')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Warning Box -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>Warning:</strong> Transferring this student will:
                            </p>
                            <ul class="mt-2 text-sm text-yellow-700 list-disc list-inside space-y-1">
                                <li>Remove all current subject enrollments</li>
                                <li>Enroll the student in all subjects of the new section</li>
                                <li>Update the enrollment history</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.enrollment.index') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition {{ $sections->isEmpty() ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ $sections->isEmpty() ? 'disabled' : '' }}>
                        Transfer Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
