@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Maintenance Mode</h1>
            <p class="text-gray-600 mt-2">Control system maintenance mode and accessibility</p>
        </div>

        <!-- Current Status -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <span class="w-3 h-3 bg-green-500 rounded-full mr-3 animate-pulse"></span>
                        System Status: Online
                    </h2>
                    <p class="text-sm text-gray-500 mt-1 ml-6">All systems operational</p>
                </div>
                <form method="POST" action="{{ route('admin.maintenance.toggle') }}">
                    @csrf
                    <button type="submit" 
                            class="px-6 py-3 bg-orange-600 text-white font-semibold rounded-lg hover:bg-orange-700 transition-colors">
                        Enable Maintenance Mode
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
            
            <form class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Maintenance Message</label>
                    <textarea name="maintenance_message" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                              placeholder="Custom message to display during maintenance...">We are currently performing scheduled maintenance. The system will be back online shortly.</textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Duration</label>
                    <select name="estimated_duration" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="30">30 minutes</option>
                        <option value="60">1 hour</option>
                        <option value="120">2 hours</option>
                        <option value="240">4 hours</option>
                        <option value="custom">Custom</option>
                    </select>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="allow_super_admin" id="allow_super_admin" checked
                           class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                    <label for="allow_super_admin" class="ml-2 text-sm text-gray-700">
                        Allow Super Admin access during maintenance
                    </label>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="notify_users" id="notify_users"
                           class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                    <label for="notify_users" class="ml-2 text-sm text-gray-700">
                        Send email notification to all active users
                    </label>
                </div>
            </form>
        </div>

        <!-- Scheduled Maintenance -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                <x-icon name="calendar" class="w-5 h-5 mr-2 text-blue-600" />
                Scheduled Maintenance
            </h2>
            
            <div class="space-y-4">
                <p class="text-sm text-gray-600">Schedule maintenance windows in advance</p>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Start Date & Time</label>
                        <input type="datetime-local" name="maintenance_start"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">End Date & Time</label>
                        <input type="datetime-local" name="maintenance_end"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                
                <button type="button" 
                        class="w-full px-4 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                    Schedule Maintenance
                </button>
                
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-sm font-medium text-gray-700 mb-3">Upcoming Scheduled Maintenance</p>
                    <div class="text-center py-8 text-gray-500">
                        <x-icon name="calendar" class="w-12 h-12 mx-auto mb-2 text-gray-400" />
                        <p class="text-sm">No maintenance scheduled</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
