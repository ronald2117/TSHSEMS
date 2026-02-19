@extends('layouts.app')

@section('page_title', 'Activity Logs')
@section('page_subtitle', 'System-wide activity and security audit trail')

@section('toolbar')
    <div class="flex items-center justify-between gap-4 w-full">
        <!-- Smart Search -->
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" 
                   id="smart-search" 
                   value="{{ request('search') }}" 
                   placeholder="Search by user, action, description, or IP address..." 
                   class="w-full pl-10 pr-10 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
            @if(request('search'))
                <a href="{{ route('admin.logs.activity') }}" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            @endif
        </div>

        <!-- Action Filter Dropdown -->
        <select id="action-filter" 
                class="px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent whitespace-nowrap">
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

    <script>
        // Smart search with debounce
        const searchInput = document.getElementById('smart-search');
        const actionFilter = document.getElementById('action-filter');
        let debounceTimer;
        
        function updateUrl() {
            const url = new URL(window.location.href);
            const searchValue = searchInput.value;
            const actionValue = actionFilter.value;
            
            if (searchValue) {
                url.searchParams.set('search', searchValue);
            } else {
                url.searchParams.delete('search');
            }
            
            if (actionValue) {
                url.searchParams.set('action', actionValue);
            } else {
                url.searchParams.delete('action');
            }
            
            window.location.href = url.toString();
        }
        
        searchInput.addEventListener('input', function(e) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                updateUrl();
            }, 500); // 500ms delay
        });

        actionFilter.addEventListener('change', function(e) {
            updateUrl();
        });
    </script>
@endsection

@section('content')
<div class="p-6">
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
                                {{ $log->created_at->format('M d, Y g:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $log->user->full_name ?? 'System' }}</div>
                                <div class="text-xs text-gray-500">{{ $log->user->email ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    @if(str_contains($log->action, 'create')) bg-green-100 text-primary-700
                                    @elseif(str_contains($log->action, 'update')) bg-blue-100 text-blue-700
                                    @elseif(str_contains($log->action, 'delete')) bg-red-100 text-red-700
                                    @elseif(str_contains($log->action, 'login')) bg-purple-100 text-purple-700
                                    @elseif(str_contains($log->action, 'logout')) bg-gray-100 text-gray-700
                                    @elseif(str_contains($log->action, 'approve')) bg-teal-100 text-teal-700
                                    @else bg-gray-100 text-gray-700
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
                                @if(request()->hasAny(['search', 'action']))
                                    No activity logs found matching your search criteria.
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
