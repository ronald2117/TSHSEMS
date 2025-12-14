<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TSHSEMS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-cover bg-center bg-no-repeat" style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ asset('tshsems_school_bg.png') }}');">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <img src="{{ asset('tshs_logo.png') }}" alt="TSHS Logo" class="h-12 w-12">
                    <span class="ml-2 text-xl font-bold text-gray-800">Taysan Senior Highschool</span>
                </div>
                <div class="flex items-center space-x-6">
                    <a href="{{ url('/') }}" class="text-gray-600 hover:text-green-600 text-sm font-medium transition">
                        Home
                    </a>
                    <a href="#" class="text-gray-600 hover:text-green-600 text-sm font-medium transition">
                        Help & Support
                    </a>
                    <a href="#" class="text-gray-600 hover:text-green-600 text-sm font-medium transition">
                        Privacy Policy
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Login Container -->
    <div class="flex items-center justify-center min-h-[calc(100vh-4rem)] py-12">
        <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <!-- Logo -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Taysan SHS</h1>
                <p class="text-gray-600 mt-2">Evaluation Management System</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
                @csrf

                <!-- Email/Login ID -->
                <div>
                    <label for="login_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Email or Login ID
                    </label>
                    <input type="text" name="login_id" id="login_id" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('login_id') border-red-500 @enderror"
                           value="{{ old('login_id') }}">
                    @error('login_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Password
                    </label>
                    <input type="password" name="password" id="password" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember"
                           class="h-4 w-4 text-green-600 rounded">
                    <label for="remember" class="ml-2 text-sm text-gray-700">
                        Remember me
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 rounded-lg transition">
                    Sign In
                </button>
            </form>

            <!-- Links -->
            <div class="mt-6 text-center text-sm">
                <p class="text-gray-600">
                    <a href="{{ route('password.request') }}" class="text-green-600 hover:text-green-700 font-medium">Forgot Password?</a>
                </p>
                <p class="text-gray-500 text-xs mt-4">
                    New user accounts must be created by system administrators.
                </p>
            </div>
        </div>
        </div>
    </div>
</body>
</html>
