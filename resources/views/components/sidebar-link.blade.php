
@props(['href', 'active' => false])

<a href="{{ $href }}"
   class="block px-4 py-2 text-sm rounded-lg transition-all duration-200
         {{ $active ? 'bg-white/10 text-white font-medium' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
    {{ $slot }}
</a>