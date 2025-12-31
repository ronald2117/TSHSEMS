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
