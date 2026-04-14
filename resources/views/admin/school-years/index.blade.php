@extends('layouts.app')
@section('page_title', 'School Years')

@section('content')
<div class="p-6">
    <div class="flex justify-end items-center mb-6">
        <a href="{{ route('admin.school-years.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg transition">
            + New School Year
        </a>
    </div>

    @if(session('success'))
        <div class="bg-primary-100 border border-primary-400 text-primary-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Academic Year</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Start Date</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">End Date</th>
                    <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Status</th>
                    <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($schoolYears as $schoolYear)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $schoolYear->name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $schoolYear->start_date->format('M d, Y') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $schoolYear->end_date->format('M d, Y') }}</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-center text-sm text-gray-600">
                            {{ $schoolYear->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center space-x-3">
                            <a href="{{ route('admin.school-years.show', $schoolYear) }}" 
                               class="text-gray-600 hover:text-gray-700 transition mb-1" 
                               title="View Details">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        No school years found. Create one to get started.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($schoolYears->hasPages())
    <div class="mt-4">
        {{ $schoolYears->links() }}
    </div>
    @endif
</div>
@endsection
