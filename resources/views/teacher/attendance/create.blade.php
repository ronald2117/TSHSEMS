@extends('layouts.app')

@section('page_title', 'Record Attendance')
@section('page_subtitle', 'Mark student attendance for today.')

@section('content')
<div class="p-6">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('teacher.attendance.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Attendance
        </a>
    </div>

    <form action="{{ route('teacher.attendance.store') }}" method="POST">
        @csrf

        <!-- Select Class and Date -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Attendance Details</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="class_schedule_id" class="block text-sm font-medium text-gray-700 mb-2">Select Class</label>
                    <select name="class_schedule_id" id="class_schedule_id" required 
                            class="cursor-pointer w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Choose a class...</option>
                        @foreach($classSchedules as $schedule)
                            <option value="{{ $schedule->id }}">
                                {{ $schedule->subject->name }} - {{ $schedule->section->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('class_schedule_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" required
                           class="cursor-pointer w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    @error('date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Student List (loaded via JavaScript after class selection) -->
        <div id="student-list" class="hidden">
            <div class="bg-white rounded-xl shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">Mark Attendance</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3" id="students-container">
                        <!-- Students will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end gap-4 mt-6">
                <a href="{{ route('teacher.attendance.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition">
                    Cancel
                </a>
                <button type="submit" class="cursor-pointer px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition">
                    Save Attendance
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('class_schedule_id').addEventListener('change', function() {
    const classId = this.value;
    const studentList = document.getElementById('student-list');
    const container = document.getElementById('students-container');
    
    if (!classId) {
        studentList.classList.add('hidden');
        return;
    }
    
    // In a real implementation, you would fetch students via AJAX
    // For now, show the section and provide a note
    studentList.classList.remove('hidden');
    container.innerHTML = '<p class="text-gray-600">Select a class to load students. (AJAX implementation needed)</p>';
});
</script>
@endsection
