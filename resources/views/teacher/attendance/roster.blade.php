@extends('layouts.app')

@section('page_title', 'Take Attendance - ' . $classSchedule->subject->name)
@section('page_subtitle', $classSchedule->section->name . ' • ' . date('F d, Y'))

@section('content')
<div class="p-6">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('teacher.attendance.index') }}" class="inline-flex items-center text-green-600 hover:text-green-700">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Attendance
        </a>
    </div>

    <!-- Class Info -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">{{ $classSchedule->subject->name }}</h2>
                <p class="text-sm text-gray-600 mt-1">{{ $classSchedule->section->name }} • {{ $students->count() }} Students</p>
            </div>
        </div>
    </div>

    <form action="{{ route('teacher.attendance.store') }}" method="POST">
        @csrf
        <input type="hidden" name="class_schedule_id" value="{{ $classSchedule->id }}">
        <input type="date" name="date" value="{{ date('Y-m-d') }}" class="cursor-pointer mb-4 px-4 py-2 border border-gray-300 rounded-lg">

        <!-- Attendance Table -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Student Roster</h3>
                <div class="flex gap-2">
                    <button type="button" onclick="markAll('Present')" class="cursor-pointer px-3 py-1 text-xs bg-green-100 text-green-700 rounded hover:bg-green-200 transition">
                        Mark All Present
                    </button>
                    <button type="button" onclick="markAll('Absent')" class="cursor-pointer px-3 py-1 text-xs bg-red-100 text-red-700 rounded hover:bg-red-200 transition">
                        Mark All Absent
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Present</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Absent</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Late</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Excused</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($students as $student)
                            @php
                                $attendance = $attendances->get($student->id);
                                $status = $attendance?->status ?? 'Present';
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                            <span class="text-green-700 font-semibold text-sm">
                                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $student->first_name }} {{ $student->last_name }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $student->studentProfile?->lrn ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="radio" name="attendance[{{ $student->id }}]" value="Present" 
                                           {{ $status === 'Present' ? 'checked' : '' }}
                                           class="cursor-pointer w-4 h-4 text-green-600 focus:ring-green-500">
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="radio" name="attendance[{{ $student->id }}]" value="Absent"
                                           {{ $status === 'Absent' ? 'checked' : '' }}
                                           class="cursor-pointer w-4 h-4 text-red-600 focus:ring-red-500">
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="radio" name="attendance[{{ $student->id }}]" value="Late"
                                           {{ $status === 'Late' ? 'checked' : '' }}
                                           class="cursor-pointer w-4 h-4 text-yellow-600 focus:ring-yellow-500">
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="radio" name="attendance[{{ $student->id }}]" value="Excused"
                                           {{ $status === 'Excused' ? 'checked' : '' }}
                                           class="cursor-pointer w-4 h-4 text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="px-6 py-4">
                                    <input type="text" name="remarks[{{ $student->id }}]" 
                                           value="{{ $attendance?->remarks ?? '' }}"
                                           placeholder="Optional notes..."
                                           class="cursor-pointer w-full px-3 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">
                                    No students enrolled in this class
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
    </form>
</div>

<script>
function markAll(status) {
    document.querySelectorAll(`input[value="${status}"]`).forEach(radio => {
        radio.checked = true;
    });
}
</script>
@endsection
