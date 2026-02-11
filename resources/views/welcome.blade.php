<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TSHSEMS - Taysan Senior High School Evaluation Management System</title>
    <link rel="icon" type="image/png" href="{{ asset('tshs_logo_whitebg.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-green-50 to-emerald-100 min-h-screen">
    <!-- Navigation -->
    <x-header active="home" />

    <!-- Hero Section -->
    <div class="relative bg-cover bg-center bg-no-repeat" style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ asset('tshsems_school_bg.png') }}');">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-32">
            <div class="text-center">
                <h1 class="text-4xl font-extrabold text-white sm:text-5xl md:text-6xl">
                    <span class="block">Taysan Senior High School</span>
                    <span class="block text-green-400 mt-2">Evaluation Management System</span>
                </h1>
                <p class="mt-6 max-w-3xl mx-auto text-lg text-white md:text-xl">
                    Streamline academic performance at Taysan Senior High School. Track, grade, and communicate efficiently—anytime, anywhere.
                </p>
                <div class="flex justify-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center mt-8 px-10 py-4 border border-transparent text-lg font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                        Access System
                        <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">System Features</h2>
                <p class="mt-4 text-xl text-gray-600">Everything you need for efficient academic management</p>
            </div>

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <div class="bg-white rounded-xl shadow-md p-8 hover:shadow-xl transition-shadow duration-300 border border-gray-100 text-center">
                    <div class="flex items-center justify-center h-14 w-14 rounded-lg bg-green-600 text-white mb-6 mx-auto">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Grade Management</h3>
                    <p class="text-gray-600 leading-relaxed">DepEd-compliant grading system with automatic calculation, transmutation, and approval workflow.</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-8 hover:shadow-xl transition-shadow duration-300 border border-gray-100 text-center">
                    <div class="flex items-center justify-center h-14 w-14 rounded-lg bg-blue-600 text-white mb-6 mx-auto">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Attendance Tracking</h3>
                    <p class="text-gray-600 leading-relaxed">Daily attendance recording with comprehensive reporting for teachers and students.</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-8 hover:shadow-xl transition-shadow duration-300 border border-gray-100 text-center">
                    <div class="flex items-center justify-center h-14 w-14 rounded-lg bg-purple-600 text-white mb-6 mx-auto">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">User Management</h3>
                    <p class="text-gray-600 leading-relaxed">Role-based access control for admins, teachers, and students with comprehensive profile management.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Benefits Section -->
    <div class="bg-gradient-to-br from-green-50 to-emerald-100 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Benefits for Everyone</h2>
                <p class="mt-4 text-xl text-gray-600">Streamlined workflows for students, teachers, and administrators</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Students Benefits -->
                <div class="bg-white rounded-2xl shadow-lg p-8 md:p-10">
                    <div class="flex items-center mb-6">
                        <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-green-600 text-white">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <h3 class="ml-4 text-2xl font-bold text-gray-900">For Students</h3>
                    </div>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-green-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700"><strong class="text-gray-900">Real-time Grade Access:</strong> View approved grades and academic performance anytime, anywhere</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-green-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700"><strong class="text-gray-900">Attendance Monitoring:</strong> Track your attendance records across all subjects</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-green-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700"><strong class="text-gray-900">Document Requests:</strong> Request academic documents digitally without hassle</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-green-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700"><strong class="text-gray-900">Transparent Grading:</strong> Understand how your grades are calculated with detailed breakdowns</span>
                        </li>
                    </ul>
                </div>

                <!-- Admin/Teachers Benefits -->
                <div class="bg-white rounded-2xl shadow-lg p-8 md:p-10">
                    <div class="flex items-center mb-6">
                        <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-blue-600 text-white">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="ml-4 text-2xl font-bold text-gray-900">For Admin & Teachers</h3>
                    </div>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700"><strong class="text-gray-900">Automated Calculations:</strong> Grade computation and transmutation handled automatically</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700"><strong class="text-gray-900">Approval Workflow:</strong> Structured grade submission and approval process</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700"><strong class="text-gray-900">Complete Audit Trail:</strong> Track all grade changes with detailed logs for compliance</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700"><strong class="text-gray-900">Centralized Management:</strong> Manage students, teachers, sections, and subjects in one place</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700"><strong class="text-gray-900">DepEd Compliance:</strong> Built following official DepEd grading standards and policies</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <x-footer />
</body>
</html>
