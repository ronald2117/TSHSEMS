<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Maintenance - TSHSEMS</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12 text-center">
            <!-- Icon -->
            <div class="mb-6">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-orange-100 rounded-full">
                    <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>

            <!-- Title -->
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                System Under Maintenance
            </h1>

            <!-- Message -->
            <div class="mb-8">
                <p class="text-lg text-gray-600 leading-relaxed">
                    {{ $message ?? 'We are currently performing scheduled maintenance. The system will be back online shortly.' }}
                </p>
            </div>

            <!-- Additional Info -->
            <div class="bg-slate-50 rounded-lg p-6 mb-6">
                <p class="text-sm text-gray-600">
                    We apologize for any inconvenience. Our team is working to improve your experience.
                </p>
                <p class="text-sm text-gray-500 mt-2">
                    Please check back shortly or contact your system administrator if you have urgent concerns.
                </p>
            </div>

            <!-- Logo/Branding -->
            <div class="pt-6 border-t border-gray-200">
                <p class="text-sm font-semibold text-gray-900">
                    Taysan Senior High School
                </p>
                <p class="text-xs text-gray-500 mt-1">
                    Evaluation Management System
                </p>
            </div>
        </div>

        <!-- Status Indicator -->
        <div class="mt-6 text-center">
            <div class="inline-flex items-center text-sm text-gray-500">
                <span class="flex h-3 w-3 mr-2">
                    <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-orange-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-orange-500"></span>
                </span>
                Maintenance in progress
            </div>
        </div>
    </div>
</body>
</html>
