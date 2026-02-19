@extends('layouts.app')

@section('page_title', 'Bulk Student Enrollment')
@section('page_subtitle', 'Import multiple students from CSV/Excel file')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Instructions Card -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">How to Use Bulk Import</h3>
                    <ol class="list-decimal list-inside space-y-2 text-sm text-blue-800">
                        <li>Download the CSV template or prepare your Excel file</li>
                        <li>Fill in student information with required columns: <span class="font-mono bg-blue-100 px-2 py-0.5 rounded">LRN, Last Name, First Name, Middle Name, Email</span></li>
                        <li>Make sure students already exist in the system (only LRN is used to match existing students)</li>
                        <li>Select the target section and enrollment date</li>
                        <li>Upload your file and submit</li>
                    </ol>
                    <div class="mt-4">
                        <a href="{{ route('admin.enrollment.bulk-import') }}?download=template" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Download CSV Template
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Form -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <form action="{{ route('admin.enrollment.bulk-import.process') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Section Selection -->
                <div class="mb-6">
                    <label for="section_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Target Section <span class="text-red-500">*</span>
                    </label>
                    <select name="section_id" 
                            id="section_id" 
                            required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('section_id') border-red-500 @enderror">
                        <option value="">Select a section...</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                {{ $section->name }} - {{ $section->strand->name }} ({{ $section->schoolYear->name }})
                            </option>
                        @endforeach
                    </select>
                    @error('section_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Enrollment Date -->
                <div class="mb-6">
                    <label for="enrollment_date" class="block text-sm font-semibold text-gray-700 mb-2">
                        Enrollment Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           name="enrollment_date" 
                           id="enrollment_date" 
                           value="{{ old('enrollment_date', date('Y-m-d')) }}"
                           required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('enrollment_date') border-red-500 @enderror">
                    @error('enrollment_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Upload -->
                <div class="mb-6">
                    <label for="file" class="block text-sm font-semibold text-gray-700 mb-2">
                        Upload File (CSV or Excel) <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center justify-center w-full">
                        <label for="file" class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                <p class="text-xs text-gray-500">CSV, XLSX, or XLS (MAX. 2MB)</p>
                                <p id="file-name" class="mt-2 text-sm text-primary-600 font-medium"></p>
                            </div>
                            <input id="file" 
                                   name="file" 
                                   type="file" 
                                   accept=".csv,.xlsx,.xls"
                                   required
                                   class="hidden" />
                        </label>
                    </div>
                    @error('file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- CSV Format Example -->
                <div class="mb-6 bg-gray-50 rounded-lg p-4">
                    <p class="text-sm font-semibold text-gray-700 mb-2">Expected CSV Format:</p>
                    <pre class="text-xs bg-white p-3 rounded border border-gray-200 overflow-x-auto"><code>LRN,Last Name,First Name,Middle Name,Email
123456789012,Dela Cruz,Juan,Santos,juan.delacruz@example.com
987654321098,Santos,Maria,Garcia,maria.santos@example.com</code></pre>
                </div>

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="font-semibold text-red-800 mb-1">Errors occurred:</p>
                                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Import Errors from Session -->
                @if(session('import_errors'))
                    <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div class="flex-1">
                                <p class="font-semibold text-yellow-800 mb-2">Import completed with some errors:</p>
                                <div class="max-h-48 overflow-y-auto">
                                    <ul class="list-disc list-inside text-sm text-yellow-700 space-y-1">
                                        @foreach(session('import_errors') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.enrollment.index') }}" 
                       class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Import Students
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Show selected file name
    document.getElementById('file').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        const fileNameDisplay = document.getElementById('file-name');
        if (fileName) {
            fileNameDisplay.textContent = `Selected: ${fileName}`;
        }
    });
</script>
@endsection
