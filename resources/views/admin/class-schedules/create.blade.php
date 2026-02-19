@extends('layouts.app')
@section('page_title', 'Create Class Schedule')
@section('page_subtitle', 'Assign a subject and teacher to a section.')

@section('toolbar')
    <div class="flex items-center justify-end gap-3 w-full">
        <a href="{{ route('admin.class-schedules.index') }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Schedules
        </a>
    </div>
@endsection

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-8">
                <form method="POST" action="{{ route('admin.class-schedules.store') }}" id="scheduleForm">
                    @csrf

                    <div class="space-y-6">
                        <!-- School Year Selection (for filtering) -->
                        <div>
                            <label for="school_year_id" class="block text-sm font-semibold text-gray-900 mb-2">
                                School Year <span class="text-red-500">*</span>
                            </label>
                            <select id="school_year_id" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                    required>
                                <option value="">Select school year</option>
                                @foreach($schoolYears as $sy)
                                    <option value="{{ $sy->id }}" {{ old('school_year_id') == $sy->id ? 'selected' : '' }}>
                                        {{ $sy->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Academic Period -->
                        <div>
                            <label for="academic_period_id" class="block text-sm font-semibold text-gray-900 mb-2">
                                Academic Period <span class="text-red-500">*</span>
                            </label>
                            <select name="academic_period_id" 
                                    id="academic_period_id" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('academic_period_id') border-red-500 @enderror"
                                    required>
                                <option value="">Select school year first</option>
                            </select>
                            @error('academic_period_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Section -->
                        <div>
                            <label for="section_id" class="block text-sm font-semibold text-gray-900 mb-2">
                                Section <span class="text-red-500">*</span>
                            </label>
                            <select name="section_id" 
                                    id="section_id" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('section_id') border-red-500 @enderror"
                                    required>
                                <option value="">Select a section</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                        {{ $section->name }} - {{ $section->strand->code }} (Grade {{ $section->grade_level }}) - {{ $section->schoolYear->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('section_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Subject -->
                        <div>
                            <label for="subject_id" class="block text-sm font-semibold text-gray-900 mb-2">
                                Subject <span class="text-red-500">*</span>
                            </label>
                            <select name="subject_id" 
                                    id="subject_id" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('subject_id') border-red-500 @enderror"
                                    required>
                                <option value="">Select a subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->code }} - {{ $subject->name }} ({{ $subject->subject_type }})
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Teacher -->
                        <div>
                            <label for="teacher_id" class="block text-sm font-semibold text-gray-900 mb-2">
                                Teacher <span class="text-red-500">*</span>
                            </label>
                            <select name="teacher_id" 
                                    id="teacher_id" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('teacher_id') border-red-500 @enderror"
                                    required>
                                <option value="">Select a teacher</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Schedule Time -->
                        <div>
                            <label for="schedule_time" class="block text-sm font-semibold text-gray-900 mb-2">
                                Schedule Time (Optional)
                            </label>
                            <input type="text" 
                                   name="schedule_time" 
                                   id="schedule_time" 
                                   value="{{ old('schedule_time') }}"
                                   placeholder="e.g., Mon/Wed/Fri 8:00-9:00 AM"
                                   maxlength="100"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('schedule_time') border-red-500 @enderror">
                            @error('schedule_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Room -->
                        <div>
                            <label for="room" class="block text-sm font-semibold text-gray-900 mb-2">
                                Room (Optional)
                            </label>
                            <input type="text" 
                                   name="room" 
                                   id="room" 
                                   value="{{ old('room') }}"
                                   placeholder="e.g., Room 101, Science Lab"
                                   maxlength="50"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('room') border-red-500 @enderror">
                            @error('room')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t">
                        <a href="{{ route('admin.class-schedules.index') }}" 
                           class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition text-sm font-medium">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition shadow-sm hover:shadow-md text-sm font-medium">
                            Create Schedule
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Load academic periods when school year changes
    document.getElementById('school_year_id').addEventListener('change', function() {
        const schoolYearId = this.value;
        const periodSelect = document.getElementById('academic_period_id');
        
        if (!schoolYearId) {
            periodSelect.innerHTML = '<option value="">Select school year first</option>';
            return;
        }
        
        periodSelect.innerHTML = '<option value="">Loading...</option>';
        
        fetch(`/admin/class-schedules/periods/${schoolYearId}`)
            .then(response => response.json())
            .then(periods => {
                periodSelect.innerHTML = '<option value="">Select academic period</option>';
                periods.forEach(period => {
                    const option = document.createElement('option');
                    option.value = period.id;
                    option.textContent = period.name;
                    periodSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading periods:', error);
                periodSelect.innerHTML = '<option value="">Error loading periods</option>';
            });
    });
</script>
@endsection
