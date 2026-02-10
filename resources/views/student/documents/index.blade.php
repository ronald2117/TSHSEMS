@extends('layouts.app')

@section('title', 'Document Requests')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">My Document Requests</h1>
        <a href="{{ route('student.documents.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Request Document
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid gap-4">
        @forelse ($requests as $request)
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">{{ $request->type }}</h3>
                        <p class="text-sm text-gray-500">Request #{{ $request->id }} • {{ $request->created_at->format('M d, Y') }}</p>
                    </div>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full 
                        @if($request->status === 'Pending') bg-yellow-100 text-yellow-800
                        @elseif($request->status === 'Processing') bg-blue-100 text-blue-800
                        @elseif($request->status === 'Ready') bg-green-100 text-green-800
                        @elseif($request->status === 'Claimed') bg-gray-100 text-gray-800
                        @else bg-red-100 text-red-800 @endif">
                        {{ $request->status }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                    <div>
                        <span class="text-gray-600">Copies:</span>
                        <span class="font-medium text-gray-800">{{ $request->copies }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Fee:</span>
                        <span class="font-medium text-gray-800">₱{{ number_format($request->fee, 2) }}</span>
                    </div>
                    <div class="col-span-2">
                        <span class="text-gray-600">Purpose:</span>
                        <p class="font-medium text-gray-800">{{ $request->purpose }}</p>
                    </div>
                </div>

                @if ($request->rejection_reason)
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                        <p class="text-sm text-red-800"><strong>Reason:</strong> {{ $request->rejection_reason }}</p>
                    </div>
                @endif

                @if ($request->status === 'Ready')
                    <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4">
                        <p class="text-sm text-green-800">
                            <strong>Your document is ready!</strong> Please proceed to the Registrar's Office to claim your document.
                        </p>
                    </div>
                @endif

                <div class="flex gap-2">
                    <a href="{{ route('student.documents.show', $request) }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View Details
                    </a>
                    @if ($request->status === 'Pending')
                        <form action="{{ route('student.documents.cancel', $request) }}" method="POST" class="inline" onsubmit="return confirm('Cancel this request?');">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium ml-4">
                                Cancel Request
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm p-6 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-500 mb-4">You haven't requested any documents yet.</p>
                <a href="{{ route('student.documents.create') }}" class="text-green-600 hover:underline font-medium">
                    Request your first document
                </a>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $requests->links() }}
    </div>
</div>
@endsection
