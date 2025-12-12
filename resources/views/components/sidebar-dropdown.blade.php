{{-- File: resources/views/components/sidebar-dropdown.blade.php --}}

@props(['title', 'icon', 'active' => false])

<div x-data="{ open: {{ $active ? 'true' : 'false' }} }" class="relative">
    <button @click="open = !open" type="button"
        class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-xl text-sm transition-all duration-200
                   {{ $active ? 'bg-white/20 text-white font-medium' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $icon !!}
            </svg>
            <span>{{ $title }}</span>
        </div>
        <svg class="w-4 h-4 flex-shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
            fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="open" x-collapse x-cloak class="mt-1 ml-4 pl-4 border-l-2 border-blue-300/30 space-y-1">
        {{ $slot }}
    </div>
</div>
