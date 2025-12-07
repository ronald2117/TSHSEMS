<!-- Top Header Bar -->
<header class="bg-white border-b border-gray-100 shadow-sm">
    <div class="px-6 py-4 flex items-center justify-between">
        <!-- Breadcrumb -->
        <div>
            <h2 class="text-lg font-semibold text-gray-900">@yield('page_title', 'Dashboard')</h2>
            <p class="text-sm text-gray-500 mt-1">@yield('page_subtitle', '')</p>
        </div>

        <!-- Header Actions -->
        <div class="flex items-center space-x-4">
            <!-- Notifications -->
            <button class="relative p-2 text-gray-600 hover:text-gray-900 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <span class="absolute top-1 right-1 w-3 h-3 bg-red-500 rounded-full"></span>
            </button>

            <!-- User Dropdown -->
            <div class="relative group">
                <button class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 transition">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->full_name) }}&background=22c55e&color=fff" 
                         alt="" class="w-8 h-8 rounded-full">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg hidden group-hover:block z-10">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                    <hr class="my-1">
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Toolbar -->
    @hasSection('toolbar')
        <div class="px-6 py-3 border-t border-gray-100 flex items-center justify-between">
            @yield('toolbar')
        </div>
    @endif
</header>
