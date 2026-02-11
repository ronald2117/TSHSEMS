@extends('layouts.app')

@section('page_title', 'Document Requests')
@section('page_subtitle', 'Manage student document requests and processing.')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Document Requests</h1>
            <p class="text-sm text-gray-600 mt-1">Manage student document requests</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Pending</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Processing</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['processing'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Ready</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['ready'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Released</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['released'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-xl shadow-sm p-1 inline-flex gap-1">
        <a href="{{ route('admin.documents.index', ['status' => 'all']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $status === 'all' ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50' }}">
            All
        </a>
        <a href="{{ route('admin.documents.index', ['status' => 'pending']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $status === 'pending' ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50' }}">
            Pending
        </a>
        <a href="{{ route('admin.documents.index', ['status' => 'processing']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $status === 'processing' ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50' }}">
            Processing
        </a>
        <a href="{{ route('admin.documents.index', ['status' => 'ready']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $status === 'ready' ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50' }}">
            Ready
        </a>
        <a href="{{ route('admin.documents.index', ['status' => 'released']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $status === 'released' ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50' }}">
            Released
        </a>
    </div>

    <!-- Requests Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Request ID
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Student
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Document Type
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Purpose
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Request Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($requests as $request)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $request->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $request->student->full_name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $request->student->studentProfile->lrn ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">
                                {{ str_replace('_', ' ', $request->document_type) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ Str::limit($request->purpose, 30) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $request->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'text-yellow-600',
                                        'processing' => 'text-blue-600',
                                        'ready' => 'text-green-600',
                                        'released' => 'text-gray-600',
                                        'rejected' => 'text-red-600',
                                    ];
                                @endphp
                                <span class="text-xs font-semibold {{ $statusColors[$request->status] ?? 'text-gray-600' }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.documents.show', $request) }}" class="text-green-600 hover:text-green-900">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                                No document requests found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
