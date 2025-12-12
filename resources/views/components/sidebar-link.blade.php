{{-- File: resources/views/components/sidebar-link.blade.php --}}

@props(['href', 'active' => false])

<a href="{{ $href }}"
    class="block px-4 py-2 rounded-lg text-sm transition-all duration-200
          {{ $active ? 'bg-white/15 text-white font-medium' : 'text-blue-100/70 hover:bg-white/10 hover:text-white' }}">
    {{ $slot }}
</a>
