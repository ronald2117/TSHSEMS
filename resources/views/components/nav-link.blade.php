@props(['href', 'active' => false, 'icon' => null])

<a href="{{ $href }}" 
   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ $active ? 'bg-green-50 text-green-700 border-l-4 border-green-600' : 'text-gray-600 hover:bg-gray-50' }}">
    @if($icon)
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M{{ $icon }}"></path>
        </svg>
    @endif
    <span class="font-medium">{{ $slot }}</span>
</a>
