@extends('layouts.app')

@section('page_title', 'Request Document')
@section('page_subtitle', 'Submit a new document request.')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <a href="{{ route('student.documents.index') }}" class="text-primary-600 hover:text-primary-700 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Document Requests
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <p class="text-sm text-blue-800">
                <strong>Note:</strong> Document requests will be processed by the Registrar's Office. Processing time may vary depending on the type of document requested. You will be notified when your document is ready for pickup.
            </p>
        </div>

        <form action="{{ route('student.documents.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Document Type *</label>
                <select name="type" 
                        id="type" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('type') border-red-500 @enderror" 
                        required>
                    <option value="">Select document type</option>
                    <option value="Form 137" {{ old('type') == 'Form 137' ? 'selected' : '' }}>Form 137 (Permanent Record) - ₱50.00</option>
                    <option value="Form 138" {{ old('type') == 'Form 138' ? 'selected' : '' }}>Form 138 (Report Card) - ₱30.00</option>
                    <option value="Certificate of Enrollment" {{ old('type') == 'Certificate of Enrollment' ? 'selected' : '' }}>Certificate of Enrollment - ₱20.00</option>
                    <option value="Certificate of Good Moral" {{ old('type') == 'Certificate of Good Moral' ? 'selected' : '' }}>Certificate of Good Moral - ₱20.00</option>
                    <option value="Transcript of Records" {{ old('type') == 'Transcript of Records' ? 'selected' : '' }}>Transcript of Records (TOR) - ₱100.00</option>
                </select>
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="copies" class="block text-sm font-medium text-gray-700 mb-2">Number of Copies *</label>
                <input type="number" 
                       name="copies" 
                       id="copies" 
                       value="{{ old('copies', 1) }}"
                       min="1"
                       max="10"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('copies') border-red-500 @enderror" 
                       required>
                <p class="mt-1 text-xs text-gray-500">Maximum of 10 copies per request</p>
                @error('copies')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="purpose" class="block text-sm font-medium text-gray-700 mb-2">Purpose *</label>
                <textarea name="purpose" 
                          id="purpose" 
                          rows="4" 
                          placeholder="e.g., For college application, For scholarship requirements, etc."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('purpose') border-red-500 @enderror" 
                          required>{{ old('purpose') }}</textarea>
                @error('purpose')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-yellow-800 mb-2">Important Reminders:</h3>
                <ul class="list-disc list-inside text-sm text-yellow-700 space-y-1">
                    <li>Processing time is typically 3-5 business days</li>
                    <li>Payment should be made at the Cashier's Office</li>
                    <li>Bring a valid ID when claiming your document</li>
                    <li>Unclaimed documents after 30 days will be archived</li>
                </ul>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="cursor-pointer bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg">
                    Submit Request
                </button>
                <a href="{{ route('student.documents.index') }}" class="text-gray-600 hover:text-gray-800">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
