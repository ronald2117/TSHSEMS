@extends('layouts.app')
@section('page_title', 'Edit Academic Period')
@section('page_subtitle', 'Update academic period details')

@section('content')
<div class="p-6">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Edit Academic Period</h2>
                <p class="mt-1 text-sm text-gray-600">Update the details of this academic period.</p>
            </div>

            <form action="{{ route('admin.academic-periods.update', $academicPeriod) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- School Year -->
                <div>
                    <label for="school_year_id" class="block text-sm font-medium text-gray-700 mb-2">School Year <span class="text-red-500">*</span></label>
                    <select name="school_year_id" id="school_year_id" required
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('school_year_id') border-red-500 @enderror">
                        <option value="">Select School Year</option>
                        @foreach($schoolYears as $schoolYear)
                            <option value="{{ $schoolYear->id }}" {{ old('school_year_id', $academicPeriod->school_year_id) == $schoolYear->id ? 'selected' : '' }}>
                                {{ $schoolYear->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('school_year_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Period Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Period Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" required
                           value="{{ old('name', $academicPeriod->name) }}"
                           placeholder="e.g., 1st Semester, 2nd Semester"
                           class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('status') border-red-500 @enderror">
                        <option value="Active" {{ old('status', $academicPeriod->status) == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Closed" {{ old('status', $academicPeriod->status) == 'Closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.academic-periods.index') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2.5 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition">
                        Update Academic Period
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
