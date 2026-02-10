@extends('layouts.app')

@section('page_title', 'System Settings')
@section('title', 'System Settings')
@section('page_subtitle', 'Configure system-wide settings and preferences' )
@section('content')
<div class="min-h-screen bg-slate-50 p-6">
    <div class="max-w-5xl mx-auto">
        <!-- Settings Form -->
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- General Settings -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <x-icon name="cog" class="w-5 h-5 mr-2 text-blue-600" />
                    General Settings
                </h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">System Name</label>
                        <input type="text" name="system_name" value="TSHSEMS" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">School Name</label>
                        <input type="text" name="school_name" value="Taysan Senior High School" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                        <input type="email" name="contact_email" value="admin@tshsems.edu.ph" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Session Settings -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <x-icon name="clock" class="w-5 h-5 mr-2 text-green-600" />
                    Session & Security
                </h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Session Timeout (minutes)</label>
                        <input type="number" name="session_timeout" value="120" min="5" max="480"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Users will be logged out after this period of inactivity</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password Minimum Length</label>
                        <input type="number" name="password_min_length" value="8" min="6" max="20"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="require_password_change" id="require_password_change" checked
                               class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        <label for="require_password_change" class="ml-2 text-sm text-gray-700">
                            Require password change every 90 days
                        </label>
                    </div>
                </div>
            </div>

            <!-- Notification Settings -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <x-icon name="bell" class="w-5 h-5 mr-2 text-purple-600" />
                    Notification Settings
                </h2>
                
                <div class="space-y-3">
                    <div class="flex items-center">
                        <input type="checkbox" name="email_notifications" id="email_notifications" checked
                               class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <label for="email_notifications" class="ml-2 text-sm text-gray-700">
                            Enable email notifications
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="grade_submission_notify" id="grade_submission_notify" checked
                               class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <label for="grade_submission_notify" class="ml-2 text-sm text-gray-700">
                            Notify admins on grade submissions
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="document_request_notify" id="document_request_notify" checked
                               class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <label for="document_request_notify" class="ml-2 text-sm text-gray-700">
                            Notify registrar on document requests
                        </label>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
