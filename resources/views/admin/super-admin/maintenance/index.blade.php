@extends('layouts.app')

@section('page_title', 'Maintenance Mode')
@section('page_subtitle', 'Control system maintenance mode and accessibility.')

@section('content')
<div class="min-h-screen bg-slate-50 p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Maintenance Mode</h1>
            <p class="text-gray-600 mt-2">Control system maintenance mode and accessibility</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
        @endif

        <!-- Current Status -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <span class="w-3 h-3 {{ $maintenanceMode ? 'bg-orange-500' : 'bg-green-500' }} rounded-full mr-3 animate-pulse"></span>
                        System Status: {{ $maintenanceMode ? 'Maintenance Mode' : 'Online' }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-1 ml-6">
                        {{ $maintenanceMode ? 'System is in maintenance mode' : 'All systems operational' }}
                    </p>
                </div>
                <form method="POST" action="{{ route('admin.maintenance.toggle') }}">
                    @csrf
                    <button type="submit" 
                            class="px-6 py-3 {{ $maintenanceMode ? 'bg-primary-600 hover:bg-primary-700' : 'bg-orange-600 hover:bg-orange-700' }} text-white font-semibold rounded-lg transition-colors">
                        {{ $maintenanceMode ? 'Disable Maintenance Mode' : 'Enable Maintenance Mode' }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Maintenance Mode Settings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                <x-icon name="cog" class="w-5 h-5 mr-2 text-orange-600" />
                Maintenance Settings
            </h2>
            
            <form method="POST" action="{{ route('admin.maintenance.toggle') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Maintenance Message</label>
                    <textarea name="maintenance_message" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                              placeholder="Custom message to display during maintenance...">{{ $maintenanceMessage }}</textarea>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="allow_super_admin" id="allow_super_admin" value="1" {{ $allowSuperAdmin ? 'checked' : '' }}
                           class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                    <label for="allow_super_admin" class="ml-2 text-sm text-gray-700">
                        Allow Super Admin access during maintenance
                    </label>
                </div>
                
                <div class="pt-4">
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
