@extends('admin.layouts.app')

@section('title', 'Sliders Management')

@section('breadcrumb')
    <span class="text-gray-400">/</span>
    <span class="text-gray-600">Frontend Management</span>
    <span class="text-gray-400">/</span>
    <span class="text-gray-900 font-semibold">Sliders</span>
@endsection

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Sliders Management</h2>
                <p class="text-gray-600 mt-1">Manage homepage and page banners</p>
            </div>
            <a href="{{ route('admin.sliders.create') }}"
                class="px-6 py-3 bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white font-semibold rounded-lg hover:shadow-lg transition-all">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create New Slider
            </a>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Sliders</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $sliders->total() }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Active</p>
                        <h3 class="text-2xl font-bold text-green-600 mt-1">
                            {{ $sliders->where('is_active', true)->count() }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Inactive</p>
                        <h3 class="text-2xl font-bold text-gray-600 mt-1">
                            {{ $sliders->where('is_active', false)->count() }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Homepage</p>
                        <h3 class="text-2xl font-bold text-purple-600 mt-1">
                            {{ $sliders->where('placement', 'homepage')->count() }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <form method="GET" class="flex flex-wrap gap-4">
                {{-- Placement Filter --}}
                <select name="placement"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                    <option value="">All Placements</option>
                    <option value="homepage" {{ request('placement') == 'homepage' ? 'selected' : '' }}>Homepage</option>
                    <option value="events" {{ request('placement') == 'events' ? 'selected' : '' }}>Events Page</option>
                    <option value="about" {{ request('placement') == 'about' ? 'selected' : '' }}>About Page</option>
                    <option value="all" {{ request('placement') == 'all' ? 'selected' : '' }}>All Pages</option>
                </select>

                {{-- Status Filter --}}
                <select name="is_active"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                </select>

                {{-- Filter Button --}}
                <button type="submit"
                    class="px-6 py-2 bg-[#0053C5] text-white font-semibold rounded-lg hover:bg-[#003d8f] transition-colors">
                    Filter
                </button>

                {{-- Reset Button --}}
                <a href="{{ route('admin.sliders.index') }}"
                    class="px-6 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                    Reset
                </a>
            </form>
        </div>

        {{-- Sliders Grid --}}
        <div class="grid grid-cols-1 gap-6">
            @forelse($sliders as $slider)
                <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="flex flex-col md:flex-row">
                        {{-- Image Preview --}}
                        <div class="md:w-1/3 bg-gray-100">
                            <img src="{{ $slider->image_url }}" alt="{{ $slider->title }}"
                                class="w-full h-48 md:h-full object-cover">
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-xl font-bold text-gray-900">{{ $slider->title }}</h3>
                                        <span
                                            class="px-2 py-0.5 text-xs font-semibold rounded {{ $slider->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $slider->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-xs font-semibold rounded">
                                            {{ ucfirst($slider->placement) }}
                                        </span>
                                    </div>
                                    @if ($slider->subtitle)
                                        <p class="text-sm text-gray-600 mb-2">{{ $slider->subtitle }}</p>
                                    @endif
                                    @if ($slider->description)
                                        <p class="text-sm text-gray-700 line-clamp-2">{{ $slider->description }}</p>
                                    @endif
                                </div>
                                <div class="ml-4 text-2xl font-bold text-gray-400">
                                    #{{ $slider->order }}
                                </div>
                            </div>

                            {{-- Meta Info --}}
                            <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-4">
                                @if ($slider->button_text)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                        </svg>
                                        Button: {{ $slider->button_text }}
                                    </div>
                                @endif
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    Position: {{ ucfirst($slider->text_position) }}
                                </div>
                                @if ($slider->active_from || $slider->active_until)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        @if ($slider->active_from)
                                            From: {{ $slider->active_from->format('d M Y') }}
                                        @endif
                                        @if ($slider->active_until)
                                            Until: {{ $slider->active_until->format('d M Y') }}
                                        @endif
                                    </div>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center space-x-2 pt-4 border-t border-gray-200">
                                {{-- Toggle Status --}}
                                <button onclick="toggleStatus({{ $slider->id }})"
                                    class="px-4 py-2 {{ $slider->is_active ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} rounded-lg transition-colors">
                                    {{ $slider->is_active ? 'Deactivate' : 'Activate' }}
                                </button>

                                {{-- Edit --}}
                                <a href="{{ route('admin.sliders.edit', $slider) }}"
                                    class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                                    Edit
                                </a>

                                {{-- Delete --}}
                                <form action="{{ route('admin.sliders.destroy', $slider) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this slider?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No sliders found</h3>
                    <p class="text-gray-600 mb-4">Get started by creating your first slider</p>
                    <a href="{{ route('admin.sliders.create') }}"
                        class="inline-block px-6 py-2 bg-[#0053C5] text-white rounded-lg hover:bg-[#003d8f]">
                        Create Slider
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($sliders->hasPages())
            <div class="bg-white rounded-xl shadow-sm p-6">
                {{ $sliders->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        function toggleStatus(sliderId) {
            if (!confirm('Are you sure you want to toggle this slider status?')) {
                return;
            }

            fetch(`/admin/sliders/${sliderId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to update status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred');
                });
        }
    </script>
@endpush
