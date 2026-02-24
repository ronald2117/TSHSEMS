@extends('layouts.app')

@section('page_title', 'School ID Card')
@section('page_subtitle', 'Upload your photo and signature for your school ID')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid md:grid-cols-2 gap-6">
        <!-- ID Photo Upload -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">ID Photo</h3>
            
            @if($profile->id_photo_path)
                <div class="mb-4">
                    <img src="{{ Storage::url($profile->id_photo_path) }}" alt="ID Photo" 
                         class="w-48 h-64 object-cover border-2 border-gray-300 rounded-lg mx-auto">
                    
                    <form action="{{ route('student.school-id.delete-photo') }}" method="POST" class="mt-4" 
                          onsubmit="return confirm('Are you sure you want to delete this photo?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition">
                            Delete Photo
                        </button>
                    </form>
                </div>
            @endif

            <form action="{{ route('student.school-id.store-photo') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="id_photo" class="block text-sm font-medium text-gray-700 mb-2">
                        Upload ID Photo
                    </label>
                    <input type="file" id="id_photo" name="id_photo" accept="image/jpeg,image/jpg,image/png" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('id_photo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-600">
                        • Format: JPEG, JPG, or PNG<br>
                        • Size: Maximum 2MB<br>
                        • Minimum dimensions: 300x400 pixels<br>
                        • Top half of body without background<br>
                        • Professional attire recommended
                    </p>
                </div>

                <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition">
                    {{ $profile->id_photo_path ? 'Update Photo' : 'Upload Photo' }}
                </button>
            </form>
        </div>

        <!-- Signature Upload -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Signature</h3>
            
            @if($profile->signature_path)
                <div class="mb-4">
                    <img src="{{ Storage::url($profile->signature_path) }}" alt="Signature" 
                         class="h-24 border-2 border-gray-300 rounded-lg mx-auto bg-white p-2">
                    
                    <form action="{{ route('student.school-id.delete-signature') }}" method="POST" class="mt-4" 
                          onsubmit="return confirm('Are you sure you want to delete this signature?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition">
                            Delete Signature
                        </button>
                    </form>
                </div>
            @endif

            <form action="{{ route('student.school-id.store-signature') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="signature" class="block text-sm font-medium text-gray-700 mb-2">
                        Upload Signature
                    </label>
                    <input type="file" id="signature" name="signature" accept="image/jpeg,image/jpg,image/png" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('signature')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-600">
                        • Format: JPEG, JPG, or PNG<br>
                        • Size: Maximum 1MB<br>
                        • Clear, legible signature<br>
                        • White or transparent background recommended
                    </p>
                </div>

                <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition">
                    {{ $profile->signature_path ? 'Update Signature' : 'Upload Signature' }}
                </button>
            </form>
        </div>
    </div>

    <!-- View ID Card Button -->
    @if($profile->id_photo_path && $profile->signature_path)
        <div class="mt-6 bg-green-50 border border-green-200 rounded-xl p-6 text-center">
            <p class="text-green-800 mb-4">
                <svg class="w-6 h-6 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Your ID card is ready! You can now view and print it.
            </p>
            <a href="{{ route('student.school-id.card') }}" 
               class="inline-block px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition">
                View School ID Card
            </a>
        </div>
    @else
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6 text-center">
            <p class="text-blue-800">
                <svg class="w-6 h-6 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                Please upload both your ID photo and signature to generate your school ID card.
            </p>
        </div>
    @endif
</div>
@endsection
