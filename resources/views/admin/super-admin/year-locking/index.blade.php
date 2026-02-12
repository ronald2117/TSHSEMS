@extends('layouts.app')
@section('page_title', 'Academic Year Locking')
@section('page_subtitle', 'Lock or unlock academic years to prevent/allow modifications')

@section('content')
<div class="min-h-screen bg-slate-50 p-6">
    <div class="max-w-6xl mx-auto">

        <!-- Info Alert -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <x-icon name="shield-check" class="w-5 h-5 text-blue-600 mt-0.5 mr-3" />
                <div>
                    <h3 class="text-sm font-semibold text-blue-900">About Year Locking</h3>
                    <p class="text-sm text-blue-700 mt-1">
                        When an academic year is locked, no changes can be made to grades, enrollments, or academic records for that year. 
                        This is typically done after grades are finalized and reported to maintain data integrity.
                    </p>
                </div>
            </div>
        </div>

        <!-- School Years Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <x-icon name="calendar" class="w-5 h-5 mr-2 text-purple-600" />
                    Academic Years
                </h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Academic Year
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Lock Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Last Modified
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($schoolYears as $year)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $year->name }}
                                </div>
                                <div class="text-xs text-gray-500">{{ $year->start_date->format('M d, Y') }} - {{ $year->end_date->format('M d, Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($year->is_active)
                                    <span class="text-xs font-semibold text-green-600">
                                        Active
                                    </span>
                                @else
                                    <span class="text-xs font-semibold text-gray-600">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($year->is_locked ?? false)
                                    <div class="flex items-center text-sm text-red-600">
                                        <x-icon name="lock-closed" class="w-4 h-4 mr-1" />
                                        <span class="font-medium">Locked</span>
                                    </div>
                                @else
                                    <div class="flex items-center text-sm text-green-600">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="font-medium">Unlocked</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $year->updated_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <form method="POST" action="{{ route('admin.year-locking.toggle', $year->id) }}" class="inline">
                                    @csrf
                                    @if($year->is_locked ?? false)
                                        <button type="submit" 
                                                class="px-4 py-2 bg-green-600 text-white text-xs font-semibold rounded-lg hover:bg-green-700 transition-colors">
                                            <x-icon name="lock-closed" class="w-4 h-4 inline mr-1" />
                                            Unlock Year
                                        </button>
                                    @else
                                        <button type="submit" 
                                                onclick="return confirm('Are you sure you want to lock this academic year? This will prevent all modifications to grades and enrollments.')"
                                                class="cursor-pointer px-4 py-2 bg-red-600 text-white text-xs font-semibold rounded-lg hover:bg-red-700 transition-colors">
                                            <x-icon name="lock-closed" class="w-4 h-4 inline mr-1" />
                                            Lock Year
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <x-icon name="calendar" class="w-12 h-12 mx-auto mb-3 text-gray-400" />
                                <p class="text-gray-500">No academic years found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Warning Notice -->
        <div class="mt-6 bg-orange-50 border border-orange-200 rounded-lg p-4">
            <div class="flex">
                <x-icon name="exclamation" class="w-5 h-5 text-orange-600 mt-0.5 mr-3" />
                <div>
                    <h3 class="text-sm font-semibold text-orange-900">Important</h3>
                    <p class="text-sm text-orange-700 mt-1">
                        Locking an academic year is a critical action. Ensure all grades have been submitted, approved, and finalized before locking. 
                        Once locked, only Super Admin can unlock the year.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
