<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help & Support - TSHSEMS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <img src="{{ asset('tshs_logo.png') }}" alt="TSHS Logo" class="h-12 w-12">
                    <span class="ml-2 text-xl font-bold text-gray-800">Taysan Senior Highschool</span>
                </div>
                <div class="flex items-center space-x-6">
                    <a href="{{ route('welcome') }}" class="text-gray-600 hover:text-green-600 text-sm font-medium transition">
                        Home
                    </a>
                    <a href="{{ route('help-support') }}" class="text-green-600 text-sm font-semibold">
                        Help & Support
                    </a>
                    <a href="{{ route('privacy-policy') }}" class="text-gray-600 hover:text-green-600 text-sm font-medium transition">
                        Privacy Policy
                    </a>
                    <a href="{{ route('login') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                        Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-600 to-emerald-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-extrabold mb-4">Help & Support</h1>
            <p class="text-xl text-green-100">Find answers to common questions and get assistance with TSHSEMS</p>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <!-- FAQ Section -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Frequently Asked Questions</h2>
            <div class="space-y-6">
                <!-- FAQ Item 1 -->
                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">How do I log in to TSHSEMS?</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Use the credentials provided by your school administrator. Click the "Login" button in the navigation bar or on the home page. 
                        Enter your username and password, then click "Sign In". If you've forgotten your password, use the "Forgot Password" link.
                    </p>
                </div>

                <!-- FAQ Item 2 -->
                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">How can students view their grades?</h3>
                    <p class="text-gray-600 leading-relaxed">
                        After logging in, students can navigate to the "Grades" section from their dashboard. Grades are visible once they've been 
                        approved by the registrar. You can view detailed breakdowns of your assessments, including Written Work, Performance Tasks, 
                        and Quarterly Assessments.
                    </p>
                </div>

                <!-- FAQ Item 3 -->
                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">How do teachers submit grades?</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Teachers can enter student scores through the "Grading" module. Once all assessments are completed, the system automatically 
                        calculates quarterly grades. Teachers then submit grades for registrar approval. All grade changes are logged for audit purposes.
                    </p>
                </div>

                <!-- FAQ Item 4 -->
                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">What is the grade calculation method?</h3>
                    <p class="text-gray-600 leading-relaxed">
                        TSHSEMS follows the DepEd grading system. Grades are calculated based on weighted averages: Written Work (25%), 
                        Performance Tasks (50%), and Quarterly Assessment (25%). The initial grade is then transmuted to the final grade 
                        on a 60-100 scale according to DepEd transmutation tables.
                    </p>
                </div>

                <!-- FAQ Item 5 -->
                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">How do I reset my password?</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Click "Forgot Password" on the login page and enter your registered email address. You'll receive a password reset link. 
                        If you don't receive the email, contact the Technical Admin for assistance.
                    </p>
                </div>

                <!-- FAQ Item 6 -->
                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">How can I request academic documents?</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Students can submit document requests through their dashboard. Navigate to "Document Requests", select the document type, 
                        provide the required details, and submit. The registrar will process your request, and you'll be notified when it's ready.
                    </p>
                </div>
            </div>
        </div>

        <!-- Contact Support Section -->
        <div class="bg-white rounded-2xl shadow-lg p-8 md:p-10 border border-gray-100">
            <div class="text-center mb-8">
                <div class="flex items-center justify-center h-16 w-16 rounded-full bg-green-100 text-green-600 mx-auto mb-4">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Need Further Assistance?</h2>
                <p class="text-xl text-gray-600">Our support team is here to help you</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Email Support -->
                <div class="text-center">
                    <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-blue-600 text-white mx-auto mb-4">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Email Support</h3>
                    <p class="text-gray-600 text-sm mb-2">Send us an email anytime</p>
                    <a href="mailto:tshsems@taysan.edu.ph" class="text-green-600 hover:text-green-700 font-medium">tshsems@taysan.edu.ph</a>
                </div>

                <!-- Phone Support -->
                <div class="text-center">
                    <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-purple-600 text-white mx-auto mb-4">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Phone Support</h3>
                    <p class="text-gray-600 text-sm mb-2">Mon-Fri, 8:00 AM - 5:00 PM</p>
                    <a href="tel:+631234567890" class="text-green-600 hover:text-green-700 font-medium">+63 123 456 7890</a>
                </div>

                <!-- In-Person Support -->
                <div class="text-center">
                    <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-orange-600 text-white mx-auto mb-4">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Visit Our Office</h3>
                    <p class="text-gray-600 text-sm mb-2">Registrar's Office</p>
                    <p class="text-green-600 font-medium">Taysan Senior High School<br>Taysan, Batangas</p>
                </div>
            </div>
        </div>

        <!-- Technical Support Info -->
        <div class="mt-12 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-8 border border-blue-100">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Technical Support</h3>
                    <p class="text-gray-700 leading-relaxed">
                        For technical issues such as login problems, system errors, or browser compatibility issues, please contact the 
                        Technical Admin at <a href="mailto:tech@taysan.edu.ph" class="text-blue-600 hover:text-blue-700 font-medium">tech@taysan.edu.ph</a>. 
                        Please include details about your device, browser, and the specific issue you're experiencing.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 mt-16">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <!-- System Info -->
                <div>
                    <h3 class="text-white text-lg font-bold mb-4">TSHSEMS</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Taysan Senior High School Evaluation Management System - A comprehensive digital platform for managing academic evaluations, grading, and student records in compliance with DepEd standards.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-white text-lg font-bold mb-4">Quick Links</h3>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('welcome') }}" class="flex items-center text-gray-400 hover:text-green-400 transition-colors text-sm">
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('login') }}" class="flex items-center text-gray-400 hover:text-green-400 transition-colors text-sm">
                                Login
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('help-support') }}" class="flex items-center text-gray-400 hover:text-green-400 transition-colors text-sm">
                                Help & Support
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('privacy-policy') }}" class="flex items-center text-gray-400 hover:text-green-400 transition-colors text-sm">
                                Privacy Policy
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-white text-lg font-bold mb-4">Contact Us</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start text-gray-400 text-sm">
                            <svg class="h-5 w-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Taysan, Batangas, Philippines</span>
                        </li>
                        <li class="flex items-start text-gray-400 text-sm">
                            <svg class="h-5 w-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>+63 123 456 7890</span>
                        </li>
                        <li class="flex items-start text-gray-400 text-sm">
                            <svg class="h-5 w-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>tshsems@taysan.edu.ph</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Copyright Section -->
            <div class="border-t border-gray-700 pt-8">
                <div class="text-center text-gray-400">
                    <p>&copy; {{ date('Y') }} Taysan Senior High School. All rights reserved. | TSHSEMS - Evaluation Management System</p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
