@extends('layouts.app')

@section('page_title', 'User Management')
@section('page_subtitle', 'Manage system users, teachers, and admin accounts.')

@section('toolbar')
    <div class="flex items-center space-x-4">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center space-x-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
            
            <select name="role" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">All Roles</option>
                <option value="super_admin" {{ request('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                <option value="academic_admin" {{ request('role') === 'academic_admin' ? 'selected' : '' }}>Academic Admin</option>
                <option value="registrar_admin" {{ request('role') === 'registrar_admin' ? 'selected' : '' }}>Registrar Admin</option>
                <option value="technical_admin" {{ request('role') === 'technical_admin' ? 'selected' : '' }}>Technical Admin</option>
                <option value="teacher" {{ request('role') === 'teacher' ? 'selected' : '' }}>Teacher</option>
            </select>
            
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            
            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition">
                Filter
            </button>
            
            @if(request()->hasAny(['search', 'role', 'status']))
                <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-800 px-4 py-2 font-medium transition">
                    Clear
                </a>
            @endif
        </form>
        
        <a href="{{ route('admin.users.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition">
            + Add User
        </a>
    </div>
@endsection

@section('content')
<div class="p-6">
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">User</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Email</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Role</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Status</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Last Login</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                @if($user->avatar_path && file_exists(public_path('storage/' . $user->avatar_path)))
                                    <img src="{{ asset('storage/' . $user->avatar_path) }}" alt="{{ $user->full_name }}" class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                        <span class="text-green-600 font-semibold text-sm">{{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-900">{{ $user->full_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $user->login_id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 capitalize">
                                {{ str_replace('_', ' ', $user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-gray-600">
                            {{ $user->last_login_at?->diffForHumans() ?? 'Never' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-yellow-600 hover:text-yellow-700 font-medium text-sm">
                                        {{ $user->is_active ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700 font-medium text-sm">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-600">
                            No users found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
@endsection
