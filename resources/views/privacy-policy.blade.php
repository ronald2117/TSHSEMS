<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - TSHSEMS</title>
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
                    <a href="{{ route('help-support') }}" class="text-gray-600 hover:text-green-600 text-sm font-medium transition">
                        Help & Support
                    </a>
                    <a href="{{ route('privacy-policy') }}" class="text-green-600 text-sm font-semibold">
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
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-extrabold mb-4">Privacy Policy</h1>
            <p class="text-xl text-blue-100">Your privacy and data security are our top priorities</p>
            <p class="text-sm text-blue-200 mt-4">Last Updated: December 31, 2025</p>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <!-- Introduction -->
        <div class="bg-white rounded-xl shadow-md p-8 mb-8 border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Introduction</h2>
            <p class="text-gray-600 leading-relaxed mb-4">
                Welcome to the Taysan Senior High School Evaluation Management System (TSHSEMS). This Privacy Policy explains how we collect, 
                use, disclose, and safeguard your personal information when you use our academic evaluation and management system.
            </p>
            <p class="text-gray-600 leading-relaxed">
                By using TSHSEMS, you agree to the collection and use of information in accordance with this policy. We are committed to 
                protecting your privacy and ensuring the security of your personal data in compliance with the Data Privacy Act of 2012 
                (Republic Act No. 10173) and DepEd regulations.
            </p>
        </div>

        <!-- Information We Collect -->
        <div class="bg-white rounded-xl shadow-md p-8 mb-8 border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Information We Collect</h2>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-3">1. Personal Information</h3>
            <p class="text-gray-600 leading-relaxed mb-4">
                We collect the following types of personal information:
            </p>
            <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6 ml-4">
                <li><strong>Student Information:</strong> Name, student ID number, contact details, academic records, grades, attendance records, enrollment history, and emergency contact information</li>
                <li><strong>Teacher Information:</strong> Name, employee ID, contact details, teaching assignments, and professional qualifications</li>
                <li><strong>Administrator Information:</strong> Name, employee ID, contact details, role designation, and administrative permissions</li>
                <li><strong>Account Information:</strong> Username, email address, encrypted password, and login activity logs</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">2. Academic Data</h3>
            <p class="text-gray-600 leading-relaxed mb-4">
                We process academic information including:
            </p>
            <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6 ml-4">
                <li>Assessment scores (Written Work, Performance Tasks, Quarterly Assessments)</li>
                <li>Quarterly grades and General Weighted Averages (GWA)</li>
                <li>Attendance records and tardiness logs</li>
                <li>Subject enrollments and class schedules</li>
                <li>Academic honors and achievements</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">3. System Usage Information</h3>
            <p class="text-gray-600 leading-relaxed mb-4">
                We automatically collect certain information when you use the system:
            </p>
            <ul class="list-disc list-inside text-gray-600 space-y-2 ml-4">
                <li>Login timestamps and IP addresses</li>
                <li>Browser type and device information</li>
                <li>Pages visited and features accessed</li>
                <li>Grade changes and audit trail information</li>
                <li>System activity logs</li>
            </ul>
        </div>

        <!-- How We Use Your Information -->
        <div class="bg-white rounded-xl shadow-md p-8 mb-8 border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">How We Use Your Information</h2>
            <p class="text-gray-600 leading-relaxed mb-4">
                We use the collected information for the following purposes:
            </p>
            <ul class="list-disc list-inside text-gray-600 space-y-2 ml-4">
                <li>To provide and maintain the evaluation management system</li>
                <li>To process and calculate grades according to DepEd standards</li>
                <li>To track and record student attendance</li>
                <li>To generate academic reports and transcripts</li>
                <li>To facilitate communication between teachers, students, and administrators</li>
                <li>To ensure compliance with DepEd policies and regulations</li>
                <li>To maintain audit trails for grade changes and system modifications</li>
                <li>To improve system functionality and user experience</li>
                <li>To ensure system security and prevent unauthorized access</li>
                <li>To fulfill legal and regulatory requirements</li>
            </ul>
        </div>

        <!-- Data Security -->
        <div class="bg-white rounded-xl shadow-md p-8 mb-8 border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Data Security</h2>
            <p class="text-gray-600 leading-relaxed mb-4">
                We implement appropriate technical and organizational security measures to protect your personal information:
            </p>
            <ul class="list-disc list-inside text-gray-600 space-y-2 mb-4 ml-4">
                <li>Encrypted password storage using industry-standard hashing algorithms</li>
                <li>Role-based access control to limit data access to authorized personnel only</li>
                <li>Secure HTTPS connections for all data transmission</li>
                <li>Regular database backups to prevent data loss</li>
                <li>Comprehensive audit logs for all grade modifications</li>
                <li>Regular security updates and system maintenance</li>
                <li>Firewall protection and intrusion detection systems</li>
            </ul>
            <p class="text-gray-600 leading-relaxed">
                While we strive to use commercially acceptable means to protect your personal information, no method of transmission over 
                the Internet or electronic storage is 100% secure. We continuously work to improve our security measures.
            </p>
        </div>

        <!-- Data Sharing and Disclosure -->
        <div class="bg-white rounded-xl shadow-md p-8 mb-8 border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Data Sharing and Disclosure</h2>
            <p class="text-gray-600 leading-relaxed mb-4">
                We do not sell, trade, or rent your personal information to third parties. We may share your information only in the 
                following circumstances:
            </p>
            <ul class="list-disc list-inside text-gray-600 space-y-2 ml-4">
                <li><strong>Within the School:</strong> With authorized teachers, administrators, and staff who need access to perform their duties</li>
                <li><strong>With Parents/Guardians:</strong> Academic records may be shared with parents or legal guardians of students</li>
                <li><strong>DepEd Compliance:</strong> Reports and data required by the Department of Education for regulatory compliance</li>
                <li><strong>Legal Requirements:</strong> When required by law, court order, or government regulation</li>
                <li><strong>Academic Transfers:</strong> With other educational institutions when students transfer (with proper authorization)</li>
            </ul>
        </div>

        <!-- Data Retention -->
        <div class="bg-white rounded-xl shadow-md p-8 mb-8 border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Data Retention</h2>
            <p class="text-gray-600 leading-relaxed mb-4">
                We retain your personal information for as long as necessary to fulfill the purposes outlined in this Privacy Policy and 
                to comply with legal and regulatory requirements:
            </p>
            <ul class="list-disc list-inside text-gray-600 space-y-2 ml-4">
                <li><strong>Academic Records:</strong> Maintained permanently for historical and transcript purposes</li>
                <li><strong>Attendance Records:</strong> Retained for a minimum of 5 years as per DepEd guidelines</li>
                <li><strong>Grade Audit Logs:</strong> Maintained indefinitely for compliance and verification purposes</li>
                <li><strong>User Accounts:</strong> Soft-deleted data retained for audit trail purposes</li>
                <li><strong>System Activity Logs:</strong> Retained for 2 years for security and troubleshooting</li>
            </ul>
        </div>

        <!-- Your Rights -->
        <div class="bg-white rounded-xl shadow-md p-8 mb-8 border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Your Rights</h2>
            <p class="text-gray-600 leading-relaxed mb-4">
                Under the Data Privacy Act of 2012, you have the following rights:
            </p>
            <ul class="list-disc list-inside text-gray-600 space-y-2 ml-4">
                <li><strong>Right to Access:</strong> Request access to your personal data stored in the system</li>
                <li><strong>Right to Correction:</strong> Request correction of inaccurate or incomplete personal data</li>
                <li><strong>Right to Object:</strong> Object to processing of your personal data under certain circumstances</li>
                <li><strong>Right to Data Portability:</strong> Request a copy of your data in a structured, commonly used format</li>
                <li><strong>Right to Erasure:</strong> Request deletion of your personal data (subject to legal retention requirements)</li>
                <li><strong>Right to Lodge a Complaint:</strong> File a complaint with the National Privacy Commission if you believe your rights have been violated</li>
            </ul>
            <p class="text-gray-600 leading-relaxed mt-4">
                To exercise these rights, please contact the school's Data Protection Officer through the contact information provided below.
            </p>
        </div>

        <!-- Cookies and Tracking -->
        <div class="bg-white rounded-xl shadow-md p-8 mb-8 border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Cookies and Tracking Technologies</h2>
            <p class="text-gray-600 leading-relaxed mb-4">
                TSHSEMS uses session cookies and local storage to maintain your login state and improve your user experience. These 
                technologies help us:
            </p>
            <ul class="list-disc list-inside text-gray-600 space-y-2 mb-4 ml-4">
                <li>Keep you logged in during your session</li>
                <li>Remember your preferences and settings</li>
                <li>Protect against unauthorized access and security threats</li>
                <li>Analyze system usage to improve functionality</li>
            </ul>
            <p class="text-gray-600 leading-relaxed">
                You can configure your browser to refuse cookies, but this may limit your ability to use certain features of the system.
            </p>
        </div>

        <!-- Children's Privacy -->
        <div class="bg-white rounded-xl shadow-md p-8 mb-8 border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Children's Privacy</h2>
            <p class="text-gray-600 leading-relaxed">
                TSHSEMS is designed for use by senior high school students (typically ages 16-18), teachers, and administrators. We take 
                special care to protect the privacy of minor students. Parents and guardians have the right to access, review, and request 
                correction of their child's information. For students under 18 years of age, parental consent may be required for certain 
                data processing activities as mandated by law.
            </p>
        </div>

        <!-- Changes to Privacy Policy -->
        <div class="bg-white rounded-xl shadow-md p-8 mb-8 border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Changes to This Privacy Policy</h2>
            <p class="text-gray-600 leading-relaxed">
                We may update this Privacy Policy from time to time to reflect changes in our practices, technology, legal requirements, 
                or other factors. We will notify users of any material changes by posting the new Privacy Policy on this page and updating 
                the "Last Updated" date. We encourage you to review this Privacy Policy periodically for any changes. Your continued use of 
                the system after changes are posted constitutes your acceptance of the updated policy.
            </p>
        </div>

        <!-- Contact Information -->
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-8 border border-green-100">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Contact Us</h2>
            <p class="text-gray-600 leading-relaxed mb-6">
                If you have any questions, concerns, or requests regarding this Privacy Policy or how we handle your personal information, 
                please contact our Data Protection Officer:
            </p>
            
            <div class="space-y-3">
                <div class="flex items-start">
                    <svg class="h-6 w-6 text-green-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <div>
                        <p class="font-semibold text-gray-900">Data Protection Officer</p>
                        <p class="text-gray-600">Taysan Senior High School</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <svg class="h-6 w-6 text-green-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <div>
                        <p class="font-semibold text-gray-900">Email</p>
                        <a href="mailto:dpo@taysan.edu.ph" class="text-green-600 hover:text-green-700">dpo@taysan.edu.ph</a>
                    </div>
                </div>

                <div class="flex items-start">
                    <svg class="h-6 w-6 text-green-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <div>
                        <p class="font-semibold text-gray-900">Phone</p>
                        <a href="tel:+631234567890" class="text-green-600 hover:text-green-700">+63 123 456 7890</a>
                    </div>
                </div>

                <div class="flex items-start">
                    <svg class="h-6 w-6 text-green-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <div>
                        <p class="font-semibold text-gray-900">Address</p>
                        <p class="text-gray-600">Taysan, Batangas, Philippines</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-green-200">
                <p class="text-sm text-gray-600">
                    <strong>National Privacy Commission:</strong> For complaints regarding data privacy violations, you may contact the 
                    National Privacy Commission at <a href="https://www.privacy.gov.ph" target="_blank" rel="noopener" class="text-green-600 hover:text-green-700">www.privacy.gov.ph</a>
                </p>
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
