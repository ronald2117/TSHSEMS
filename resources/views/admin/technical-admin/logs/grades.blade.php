@extends('layouts.app')

@section('page_title', 'Grade Audit Logs')
@section('page_subtitle', 'Track all grade changes for compliance and transparency.')

@section('content')
<div class="p-6">
    <!-- Filter Form -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Filter Records</h3>
        </div>
        <form method="GET" action="{{ route('admin.audit.grades') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" 
                       placeholder="Student name or ID..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>

            <div>
                <label for="changed_by" class="block text-sm font-medium text-gray-700 mb-1">Changed By</label>
                <input type="text" id="changed_by" name="changed_by" value="{{ request('changed_by') }}" 
                       placeholder="User name..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>

            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" 
                       class="cursor-pointer w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>

            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" 
                       class="cursor-pointer w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="cursor-pointer flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition">
                    Apply Filters
                </button>
                <a href="{{ route('admin.audit.grades') }}" 
                   class="cursor-pointer px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date & Time
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Student
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Changed By
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Old Grade
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            New Grade
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Reason
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $log->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $log->quarterlyGrade->student->full_name ?? 'N/A' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $log->quarterlyGrade->student->studentProfile->student_id ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->user->name ?? 'System' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-xs font-semibold text-gray-600">
                                    {{ number_format($log->old_grade, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $log->new_grade > $log->old_grade ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ number_format($log->new_grade, 2) }}
                                    @if($log->new_grade > $log->old_grade)
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ Str::limit($log->reason, 40) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-600">
                                @if(request()->hasAny(['search', 'changed_by', 'date_from', 'date_to']))
                                    No grade audit logs found matching your filter criteria.
                                @else
                                    No grade audit logs found.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
