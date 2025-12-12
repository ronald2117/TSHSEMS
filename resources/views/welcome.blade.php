<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TSHSEMS - Taysan Senior High School Evaluation Management System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-green-50 to-emerald-100 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <img src="{{ asset('tshs_logo.png') }}" alt="TSHS Logo" class="h-12 w-12">
                    <span class="ml-2 text-xl font-bold text-gray-800">Taysan Senior Highschool</span>
                </div>
                <div class="flex items-center">
                    <a href="{{ route('login') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                        Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                <span class="block">Taysan Senior High School</span>
                <span class="block text-green-600">Evaluation Management System</span>
            </h1>
            <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                A comprehensive digital platform for managing academic evaluations, grading, attendance, and student records in compliance with DepEd standards.
            </p>
            <div class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8">
                <div class="rounded-md shadow">
                    <a href="{{ route('login') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 md:py-4 md:text-lg md:px-10">
                        Access System
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-extrabold text-gray-900">System Features</h2>
            <p class="mt-4 text-lg text-gray-500">Everything you need for efficient academic management</p>
        </div>

        <!-- <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3"> -->
            <!-- Feature 1 -->
            <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition">
                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-600 text-white mb-4">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Grade Management</h3>
                <p class="text-gray-500">DepEd-compliant grading system with automatic calculation, transmutation, and approval workflow.</p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition">
                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-600 text-white mb-4">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Attendance Tracking</h3>
                <p class="text-gray-500">Daily attendance recording with comprehensive reporting for teachers and students.</p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition">
                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-purple-600 text-white mb-4">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">User Management</h3>
                <p class="text-gray-500">Role-based access control for admins, teachers, and students with comprehensive profile management.</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Taysan Senior High School. All rights reserved.</p>
                <p class="mt-2 text-sm">TSHSEMS - Evaluation Management System</p>
                <p class="mt-4 text-xs text-gray-500">DepEd-Compliant Academic Management Platform</p>
            </div>
        </div>
    </footer>
</body>
</html>
