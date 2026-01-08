@extends('layouts.app')

@section('title', 'Document Request Details')

@section('content')
<div class="space-y-6 max-w-4xl">
    <!-- Page Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.documents.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1">
            <h1 class="text-2xl font-bold text-gray-900">Document Request #{{ $documentRequest->id }}</h1>
            <p class="text-sm text-gray-600 mt-1">Review and process document request</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Request Details Card -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Request Information</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500 mb-1">Student Name</p>
                <p class="font-medium text-gray-900">{{ $documentRequest->student->user->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Student ID</p>
                <p class="font-medium text-gray-900">{{ $documentRequest->student->student_id }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Section</p>
                <p class="font-medium text-gray-900">{{ $documentRequest->student->section->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Document Type</p>
                <p class="font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $documentRequest->document_type) }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-gray-500 mb-1">Purpose</p>
                <p class="font-medium text-gray-900">{{ $documentRequest->purpose }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Copies Requested</p>
                <p class="font-medium text-gray-900">{{ $documentRequest->copies }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Request Date</p>
                <p class="font-medium text-gray-900">{{ $documentRequest->created_at->format('F d, Y g:i A') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Current Status</p>
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'processing' => 'bg-blue-100 text-blue-800',
                        'ready' => 'bg-green-100 text-green-800',
                        'released' => 'bg-gray-100 text-gray-800',
                        'rejected' => 'bg-red-100 text-red-800',
                    ];
                @endphp
                <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full {{ $statusColors[$documentRequest->status] }}">
                    {{ ucfirst($documentRequest->status) }}
                </span>
            </div>
            @if($documentRequest->ready_date)
            <div>
                <p class="text-sm text-gray-500 mb-1">Ready Date</p>
                <p class="font-medium text-gray-900">{{ $documentRequest->ready_date->format('F d, Y') }}</p>
            </div>
            @endif
            @if($documentRequest->remarks)
            <div class="md:col-span-2">
                <p class="text-sm text-gray-500 mb-1">Remarks</p>
                <p class="font-medium text-gray-900">{{ $documentRequest->remarks }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Update Status Form -->
    <form action="{{ route('admin.documents.update-status', $documentRequest) }}" method="POST" class="bg-white rounded-xl shadow-sm p-6">
        @csrf
        @method('PUT')
        
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Update Request Status</h2>
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="pending" {{ $documentRequest->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ $documentRequest->status === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="ready" {{ $documentRequest->status === 'ready' ? 'selected' : '' }}>Ready for Pickup</option>
                    <option value="released" {{ $documentRequest->status === 'released' ? 'selected' : '' }}>Released</option>
                    <option value="rejected" {{ $documentRequest->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Remarks</label>
                <textarea name="remarks" rows="3" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    placeholder="Add any notes or remarks...">{{ old('remarks', $documentRequest->remarks) }}</textarea>
            </div>
        </div>

        <div class="mt-6 flex gap-4">
            <button type="submit" class="px-6 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition">
                Update Status
            </button>
            <a href="{{ route('admin.documents.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
