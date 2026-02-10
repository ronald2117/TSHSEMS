@props(['title', 'icon' => null])

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition']) }}>
    @if($icon)
        <div class="w-12 h-12 {{ $icon['bg'] }} rounded-full flex items-center justify-center mx-auto mt-4">
            <svg class="w-6 h-6 {{ $icon['color'] }}" fill="currentColor" viewBox="0 0 24 24">
                {!! $icon['svg'] !!}
            </svg>
        </div>
    @else
        <img src="{{ $slot }}" alt="" class="w-12 h-12 rounded-full mx-auto mt-4 object-cover">
    @endif

    <div class="p-4 text-center">
        <h3 class="font-semibold text-gray-900">{{ $title }}</h3>
    </div>

    {{ $slot }}
</div>
