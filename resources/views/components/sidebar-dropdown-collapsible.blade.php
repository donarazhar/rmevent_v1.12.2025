{{-- File: resources/views/components/sidebar-dropdown-collapsible.blade.php --}}

@props(['title', 'icon', 'active' => false])

<div x-data="{ open: {{ $active ? 'true' : 'false' }} }" class="relative">
    {{-- Collapsed State: Icon Only with Popover --}}
    <template x-if="sidebarCollapsed">
        <div class="relative" x-data="{ showPopover: false }">
            <button @mouseenter="showPopover = true" @mouseleave="showPopover = false"
                class="w-full flex items-center justify-center p-2.5 rounded-xl transition-all duration-200
                       {{ $active ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <div
                    class="w-9 h-9 rounded-lg flex items-center justify-center {{ $active ? 'bg-white/10' : 'bg-white/5' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        {!! $icon !!}
                    </svg>
                </div>
            </button>

            {{-- Popover Menu --}}
            <div x-show="showPopover" @mouseenter="showPopover = true" @mouseleave="showPopover = false"
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-2"
                x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-2"
                class="absolute left-full top-0 ml-2 w-56 bg-[#003280] rounded-xl shadow-xl py-2 z-50">
                <div class="px-4 py-2 border-b border-white/10">
                    <span class="text-sm font-semibold text-white">{{ $title }}</span>
                </div>
                <div class="py-1">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </template>

    {{-- Expanded State: Normal Dropdown --}}
    <template x-if="!sidebarCollapsed">
        <div>
            <button @click="open = !open"
                class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl text-sm transition-all duration-200
                       {{ $active ? 'bg-white/20 text-white font-medium' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <div
                        class="w-9 h-9 rounded-lg flex items-center justify-center {{ $active ? 'bg-white/10' : 'bg-white/5' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $icon !!}
                        </svg>
                    </div>
                    <span>{{ $title }}</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open" x-collapse x-cloak class="mt-1 ml-4 pl-4 border-l border-white/10 space-y-1">
                {{ $slot }}
            </div>
        </div>
    </template>
</div>
