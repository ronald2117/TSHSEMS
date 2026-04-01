@extends('layouts.app')

@section('page_title', 'Activity Logs')
@section('page_subtitle', 'System-wide activity and security audit trail')

@section('content')
<div class="p-6">
    <!-- Filter Form -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Filter Records</h3>
        </div>
        <form method="GET" action="{{ route('admin.audit.activity') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" 
                       placeholder="User, description, or IP..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>

            <div>
                <label for="action" class="block text-sm font-medium text-gray-700 mb-1">Action Type</label>
                <select id="action" name="action" 
                        class="cursor-pointer w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">All Actions</option>
                    <option value="login" {{ request('action') === 'login' ? 'selected' : '' }}>Login</option>
                    <option value="logout" {{ request('action') === 'logout' ? 'selected' : '' }}>Logout</option>
                    <option value="create" {{ request('action') === 'create' ? 'selected' : '' }}>Create</option>
                    <option value="update" {{ request('action') === 'update' ? 'selected' : '' }}>Update</option>
                    <option value="delete" {{ request('action') === 'delete' ? 'selected' : '' }}>Delete</option>
                    <option value="approve" {{ request('action') === 'approve' ? 'selected' : '' }}>Approve</option>
                    <option value="return" {{ request('action') === 'return' ? 'selected' : '' }}>Return</option>
                    <option value="override" {{ request('action') === 'override' ? 'selected' : '' }}>Override</option>
                    <option value="backup" {{ request('action') === 'backup' ? 'selected' : '' }}>Backup</option>
                    <option value="password_reset" {{ request('action') === 'password_reset' ? 'selected' : '' }}>Password Reset</option>
                </select>
            </div>

            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" 
                       class="cursor-pointer w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>

            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" 
                       class="cursor-pointer w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="cursor-pointer flex-1 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium transition">
                    Apply
                </button>
                <a href="{{ route('admin.audit.activity') }}" 
                   class="cursor-pointer px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition">
                    Reset
                </a>
            </div>
        </form>
    </div>
    <!-- Logs Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Timestamp</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">User</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Action</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Description</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $log->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $log->user->full_name ?? 'System' }}</div>
                                <div class="text-xs text-gray-500">{{ $log->user->email ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    @if(str_contains($log->action, 'create')) text-primary-700
                                    @elseif(str_contains($log->action, 'update')) text-blue-700
                                    @elseif(str_contains($log->action, 'delete')) text-red-700
                                    @elseif(str_contains($log->action, 'login')) text-purple-700
                                    @elseif(str_contains($log->action, 'logout')) text-gray-700
                                    @elseif(str_contains($log->action, 'approve')) text-teal-700
                                    @else text-gray-700
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $log->description }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono">
                                {{ $log->ip_address }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-600">
                                @if(request()->hasAny(['search', 'action', 'date_from', 'date_to']))
                                    No activity logs found matching your filter criteria.
                                @else
                                    No activity logs found.
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
