@extends('layouts.app')

@section('page_title', 'My Attendance')
@section('page_subtitle', 'Track your daily attendance records.')

@section('content')
<div class="p-6">
    <!-- Attendance Summary -->
    <div class="grid grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-4 text-center">
            <p class="text-gray-600 text-sm mb-2">Present</p>
            <p class="text-2xl font-bold text-green-600">{{ $summary['present'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 text-center">
            <p class="text-gray-600 text-sm mb-2">Late</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $summary['late'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 text-center">
            <p class="text-gray-600 text-sm mb-2">Absent</p>
            <p class="text-2xl font-bold text-red-600">{{ $summary['absent'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 text-center">
            <p class="text-gray-600 text-sm mb-2">Excused</p>
            <p class="text-2xl font-bold text-blue-600">{{ $summary['excused'] }}</p>
        </div>
    </div>

    <!-- Attendance Records -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Attendance History</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Date</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Subject</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($attendances as $attendance)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-gray-900">{{ $attendance->date->format('M d, Y') }}</td>
                        <td class="px-6 py-3 text-gray-600">{{ $attendance->classSchedule->subject->name }}</td>
                        <td class="px-6 py-3 text-center">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ match($attendance->status) {
                                'Present' => 'bg-green-100 text-green-800',
                                'Absent' => 'bg-red-100 text-red-800',
                                'Late' => 'bg-yellow-100 text-yellow-800',
                                'Excused' => 'bg-blue-100 text-blue-800',
                                default => 'bg-gray-100 text-gray-800',
                            } }}">
                                {{ $attendance->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-gray-600">
                            No attendance records yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $attendances->links() }}
    </div>
</div>
@endsection
