@extends('layouts.app')

@section('page_title', 'Activity Logs')
@section('title', 'Activity Logs')
@section('page_subtitle', 'System-wide activity and security audit trail' )
@section('content')

<div class="p-6">
    <!-- Filter Form -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Filter Records</h3>
        </div>
        <form method="GET" action="{{ route('admin.super-admin.audit.activity-logs') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" 
                       placeholder="User, description, or IP..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>

            <div>
                <label for="action" class="block text-sm font-medium text-gray-700 mb-1">Action Type</label>
                <select id="action" name="action" 
                        class="cursor-pointer w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
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
                       class="cursor-pointer w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="cursor-pointer flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition">
                    Apply Filters
                </button>
                <a href="{{ route('admin.super-admin.audit.activity-logs') }}" 
                   class="cursor-pointer px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Activity Logs Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Timestamp
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Description
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                IP Address
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
                                @if($log->user)
                                    <div class="text-sm font-medium text-gray-900">{{ $log->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $log->user->email }}</div>
                                @else
                                    <span class="text-sm text-gray-400">System</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    @if(str_contains($log->action, 'LOGIN')) text-green-800
                                    @elseif(str_contains($log->action, 'DELETE')) text-red-800
                                    @elseif(str_contains($log->action, 'UPDATE')) text-blue-800
                                    @elseif(str_contains($log->action, 'CREATE')) text-purple-800
                                    @else text-gray-800
                                    @endif">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ Str::limit($log->description, 80) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->ip_address }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <x-icon name="clipboard-list" class="w-12 h-12 mx-auto mb-3 text-gray-400" />
                                <p class="text-gray-500">No activity logs found</p>
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
@endsection
