@props(['active' => 'login'])

<nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <img src="{{ asset('tshs_logo.png') }}" alt="TSHS Logo" class="h-12 w-12">
                <span class="ml-2 text-xl font-bold text-gray-800">Taysan Senior Highschool</span>
            </div>
            <div class="flex items-center space-x-6">
                <a href="{{ route('welcome') }}" class="{{ $active === 'home' ? 'text-green-600 font-semibold' : 'text-gray-600 hover:text-green-600' }} text-sm font-medium transition">
                    Home
                </a>
                <a href="{{ route('help-support') }}" class="{{ $active === 'help-support' ? 'text-green-600 font-semibold' : 'text-gray-600 hover:text-green-600' }} text-sm font-medium transition">
                    Help & Support
                </a>
                <a href="{{ route('privacy-policy') }}" class="{{ $active === 'privacy-policy' ? 'text-green-600 font-semibold' : 'text-gray-600 hover:text-green-600' }} text-sm font-medium transition">
                    Privacy Policy
                </a>
                <a href="{{ route('login') }}" class="{{ $active === 'login' ? 'bg-white border-2 border-green-600 text-green-600' : 'bg-green-600 hover:bg-green-700 text-white' }} px-4 py-2 rounded-lg text-sm font-medium transition">
                    Login
                </a>
            </div>
        </div>
    </div>
</nav>
