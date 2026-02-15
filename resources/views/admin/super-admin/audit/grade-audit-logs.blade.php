@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 p-6">
    <div class="max-w-7xl mx-auto">

        <!-- Info Alert -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <x-icon name="shield-check" class="w-5 h-5 text-blue-600 mt-0.5 mr-3" />
                <div>
                    <h3 class="text-sm font-semibold text-blue-900">Compliance Note</h3>
                    <p class="text-sm text-blue-700 mt-1">
                        All grade changes are permanently recorded here for DepEd compliance. This audit trail cannot be modified or deleted.
                    </p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
            <div class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" placeholder="Search by student name..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="min-w-[150px]">
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Quarters</option>
                        <option value="1">1st Quarter</option>
                        <option value="2">2nd Quarter</option>
                        <option value="3">3rd Quarter</option>
                        <option value="4">4th Quarter</option>
                    </select>
                </div>
                <div class="min-w-[150px]">
                    <select class="cursor-pointer  w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Time</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                    </select>
                </div>
                <button class="cursor-pointer  px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                    Filter
                </button>
            </div>
        </div>

        <!-- Grade Audit Logs Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date & Time
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Student
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Modified By
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Field Changed
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Old Value
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                New Value
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
                                @if($log->quarterlyGrade && $log->quarterlyGrade->student)
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $log->quarterlyGrade->student->full_name ?? 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Quarter {{ $log->quarterlyGrade->quarter ?? 'N/A' }}
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">Record Deleted</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($log->user)
                                    <div class="text-sm font-medium text-gray-900">{{ $log->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $log->user->role }}</div>
                                @else
                                    <span class="text-sm text-gray-400">System</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <span class="px-2 py-1 text-gray-500 text-xs font-medium">
                                    {{ $log->field_changed ?? 'grade' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-red-600">
                                    {{ $log->old_grade ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-green-600">
                                    {{ $log->new_grade ?? '-' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <x-icon name="chart-bar" class="w-12 h-12 mx-auto mb-3 text-gray-400" />
                                <p class="text-gray-500">No grade audit logs found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $logs->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
