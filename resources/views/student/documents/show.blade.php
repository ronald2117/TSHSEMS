@extends('layouts.app')

@section('title', 'Document Request Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <a href="{{ route('student.documents.index') }}" class="text-green-600 hover:text-green-700 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to My Requests
        </a>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $document->type }}</h1>
                        <p class="text-sm text-gray-600 mt-1">Request #{{ $document->id }}</p>
                    </div>
                    <span class="px-4 py-1.5 text-sm font-medium rounded-full
                        @switch($document->status)
                            @case('Pending')
                                bg-yellow-100 text-yellow-800
                                @break
                            @case('Processing')
                                bg-blue-100 text-blue-800
                                @break
                            @case('Ready')
                                bg-green-100 text-green-800
                                @break
                            @case('Released')
                                bg-gray-100 text-gray-800
                                @break
                            @case('Rejected')
                                bg-red-100 text-red-800
                                @break
                            @default
                                bg-gray-100 text-gray-800
                        @endswitch
                    ">
                        {{ $document->status }}
                    </span>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <!-- Request Details -->
                <div>
                    <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Request Details</h2>
                    <dl class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Document Type</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $document->type }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Number of Copies</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $document->copies }}</dd>
                        </div>
                        <div class="col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Purpose</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $document->purpose }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Fee</dt>
                            <dd class="mt-1 text-sm font-semibold text-green-600">₱{{ number_format($document->fee, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Date Requested</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $document->created_at->format('M d, Y h:i A') }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Processing Information -->
                @if($document->processed_by || $document->processed_at)
                <div class="pt-6 border-t border-gray-200">
                    <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Processing Information</h2>
                    <dl class="grid grid-cols-2 gap-4">
                        @if($document->processedBy)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Processed By</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $document->processedBy->full_name ?? 'Admin' }}</dd>
                        </div>
                        @endif
                        @if($document->processed_at)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Processed Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($document->processed_at)->format('M d, Y h:i A') }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
                @endif

                <!-- Rejection Reason (if applicable) -->
                @if($document->status === 'Rejected' && $document->rejection_reason)
                <div class="pt-6 border-t border-gray-200">
                    <div class="bg-red-50 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Rejection Reason</h3>
                                <p class="mt-1 text-sm text-red-700">{{ $document->rejection_reason }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Status Timeline -->
                <div class="pt-6 border-t border-gray-200">
                    <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Status Timeline</h2>
                    <div class="relative">
                        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                        
                        <!-- Submitted -->
                        <div class="relative flex items-start mb-4">
                            <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center z-10">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Request Submitted</p>
                                <p class="text-xs text-gray-500">{{ $document->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>

                        <!-- Processing -->
                        <div class="relative flex items-start mb-4">
                            <div class="w-8 h-8 rounded-full {{ in_array($document->status, ['Processing', 'Ready', 'Released']) ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center z-10">
                                @if(in_array($document->status, ['Processing', 'Ready', 'Released']))
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <span class="w-2 h-2 bg-white rounded-full"></span>
                                @endif
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium {{ in_array($document->status, ['Processing', 'Ready', 'Released']) ? 'text-gray-900' : 'text-gray-400' }}">Processing</p>
                                <p class="text-xs text-gray-500">Document is being prepared</p>
                            </div>
                        </div>

                        <!-- Ready for Pickup -->
                        <div class="relative flex items-start mb-4">
                            <div class="w-8 h-8 rounded-full {{ in_array($document->status, ['Ready', 'Released']) ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center z-10">
                                @if(in_array($document->status, ['Ready', 'Released']))
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <span class="w-2 h-2 bg-white rounded-full"></span>
                                @endif
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium {{ in_array($document->status, ['Ready', 'Released']) ? 'text-gray-900' : 'text-gray-400' }}">Ready for Pickup</p>
                                <p class="text-xs text-gray-500">Please proceed to the Registrar's Office</p>
                            </div>
                        </div>

                        <!-- Released -->
                        <div class="relative flex items-start">
                            <div class="w-8 h-8 rounded-full {{ $document->status === 'Released' ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center z-10">
                                @if($document->status === 'Released')
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <span class="w-2 h-2 bg-white rounded-full"></span>
                                @endif
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium {{ $document->status === 'Released' ? 'text-gray-900' : 'text-gray-400' }}">Released</p>
                                <p class="text-xs text-gray-500">Document has been claimed</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            @if($document->status === 'Pending')
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <form action="{{ route('student.documents.cancel', $document) }}" method="POST" 
                      onsubmit="return confirm('Are you sure you want to cancel this request?')">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-red-600 bg-white border border-red-300 rounded-lg hover:bg-red-50 transition">
                        Cancel Request
                    </button>
                </form>
            </div>
            @endif

            @if($document->status === 'Ready')
            <div class="px-6 py-4 border-t border-gray-200 bg-green-50">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="ml-3 text-sm text-green-700">
                        <strong>Your document is ready!</strong> Please proceed to the Registrar's Office with the fee of ₱{{ number_format($document->fee, 2) }} to claim your document.
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
