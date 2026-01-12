@extends('layouts.app')

@section('title', 'Reports & Analytics')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Reports & Analytics</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Student Reports -->
        <a href="{{ route('admin.reports.students') }}" class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Student List</h3>
                    <p class="text-sm text-gray-600">View and export student masterlist</p>
                </div>
            </div>
        </a>

        <!-- Grades Summary -->
        <a href="{{ route('admin.reports.grades') }}" class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Grades Summary</h3>
                    <p class="text-sm text-gray-600">View grades by class and section</p>
                </div>
            </div>
        </a>

        <!-- Attendance Reports -->
        <a href="{{ route('admin.reports.attendance') }}" class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="bg-purple-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Attendance Summary</h3>
                    <p class="text-sm text-gray-600">View attendance reports</p>
                </div>
            </div>
        </a>
    </div>

    <div class="mt-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Official Forms & Documents</h2>
        
        <div class="bg-white rounded-xl shadow-sm p-6">
            <p class="text-gray-600 mb-4">Generate official DepEd forms and documents:</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-2">Form 137 (Permanent Record)</h3>
                    <p class="text-sm text-gray-600 mb-3">Complete academic record of a student</p>
                    <p class="text-xs text-gray-500">Select a student from the Student List to generate Form 137</p>
                </div>

                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-2">Form 138 (Report Card)</h3>
                    <p class="text-sm text-gray-600 mb-3">Quarterly grades report card</p>
                    <p class="text-xs text-gray-500">Select a student from the Student List to generate Form 138</p>
                </div>

                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-2">Section Master List</h3>
                    <p class="text-sm text-gray-600 mb-3">Complete list of students per section</p>
                    <p class="text-xs text-gray-500">Available when viewing section details</p>
                </div>

                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-2">Custom Reports</h3>
                    <p class="text-sm text-gray-600 mb-3">Generate custom analytics and summaries</p>
                    <p class="text-xs text-gray-500">Use filters in each report page</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
