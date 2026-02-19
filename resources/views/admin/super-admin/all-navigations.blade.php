@extends('layouts.app')

@section('page_title', 'All Admin Navigations')
@section('title', 'All Admin Navigations')
@section('page_subtitle', 'Super Admin override access to all administrative functions' )
@section('content')
<div class="min-h-screen bg-slate-50 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Navigation Groups -->
        <div class="space-y-8">
            @foreach($navigationGroups as $groupKey => $group)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Group Header -->
                <div class="p-6 border-b border-gray-200 
                    @if($group['color'] === 'blue') bg-gradient-to-r from-blue-50 to-blue-100 @endif
                    @if($group['color'] === 'green') bg-gradient-to-r from-green-50 to-green-100 @endif
                    @if($group['color'] === 'purple') bg-gradient-to-r from-purple-50 to-purple-100 @endif
                    @if($group['color'] === 'red') bg-gradient-to-r from-red-50 to-red-100 @endif
                    @if($group['color'] === 'orange') bg-gradient-to-r from-orange-50 to-orange-100 @endif">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center
                                @if($group['color'] === 'blue') bg-blue-500 @endif
                                @if($group['color'] === 'green') bg-green-500 @endif
                                @if($group['color'] === 'purple') bg-purple-500 @endif
                                @if($group['color'] === 'red') bg-red-500 @endif
                                @if($group['color'] === 'orange') bg-orange-500 @endif">
                                <x-icon name="{{ $group['icon'] }}" class="w-6 h-6 text-white" />
                            </div>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-xl font-semibold text-gray-900">{{ $group['title'] }}</h2>
                            <p class="text-gray-600 text-sm mt-1">{{ $group['description'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation Links Grid -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($group['links'] as $link)
                        @php
                            $hoverBorder = match($group['color']) {
                                'blue' => 'hover:border-blue-300 hover:bg-blue-50',
                                'green' => 'hover:border-green-300 hover:bg-green-50',
                                'purple' => 'hover:border-purple-300 hover:bg-purple-50',
                                'red' => 'hover:border-red-300 hover:bg-red-50',
                                'orange' => 'hover:border-orange-300 hover:bg-orange-50',
                                default => ''
                            };
                            $iconBg = match($group['color']) {
                                'blue' => 'group-hover:bg-blue-100',
                                'green' => 'group-hover:bg-green-100',
                                'purple' => 'group-hover:bg-purple-100',
                                'red' => 'group-hover:bg-red-100',
                                'orange' => 'group-hover:bg-orange-100',
                                default => ''
                            };
                            $iconColor = match($group['color']) {
                                'blue' => 'group-hover:text-blue-600',
                                'green' => 'group-hover:text-primary-600',
                                'purple' => 'group-hover:text-purple-600',
                                'red' => 'group-hover:text-red-600',
                                'orange' => 'group-hover:text-orange-600',
                                default => ''
                            };
                            $textColor = match($group['color']) {
                                'blue' => 'group-hover:text-blue-700',
                                'green' => 'group-hover:text-primary-700',
                                'purple' => 'group-hover:text-purple-700',
                                'red' => 'group-hover:text-red-700',
                                'orange' => 'group-hover:text-orange-700',
                                default => ''
                            };
                        @endphp
                        <a href="{{ route($link['route']) }}" 
                           class="group block p-4 rounded-lg border border-gray-200 transition-all duration-200 {{ $hoverBorder }}">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center transition-colors duration-200 {{ $iconBg }}">
                                        <x-icon name="{{ $link['icon'] }}" class="w-5 h-5 text-gray-600 {{ $iconColor }}" />
                                    </div>
                                </div>
                                <div class="ml-3 flex-1">
                                    <h3 class="text-sm font-semibold text-gray-900 transition-colors {{ $textColor }}">
                                        {{ $link['name'] }}
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">
                                        {{ $link['description'] }}
                                    </p>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
