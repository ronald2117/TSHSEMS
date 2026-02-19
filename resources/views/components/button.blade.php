@props(['variant' => 'primary'])

@php
    $classes = match($variant) {
        'primary' => 'bg-primary-600 hover:bg-primary-700 text-white',
        'secondary' => 'bg-gray-200 hover:bg-gray-300 text-gray-800',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white',
        'outline' => 'border border-gray-300 text-gray-700 hover:bg-gray-50',
        default => 'bg-primary-600 hover:bg-primary-700 text-white',
    };
@endphp

<button {{ $attributes->merge(['class' => "px-4 py-2 rounded-lg font-medium transition flex items-center space-x-2 {$classes}"]) }}>
    {{ $slot }}
</button>
