@extends('admin.layouts.app')

@section('title', 'Edit Slider - ' . $slider->title)

@section('breadcrumb')
    <span class="text-gray-400">/</span>
    <span class="text-gray-600">Frontend Management</span>
    <span class="text-gray-400">/</span>
    <a href="{{ route('admin.sliders.index') }}" class="text-gray-600 hover:text-gray-900">Sliders</a>
    <span class="text-gray-400">/</span>
    <span class="text-gray-900 font-semibold">Edit</span>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto">
        <form action="{{ route('admin.sliders.update', $slider) }}" method="POST" enctype="multipart/form-data"
            id="update-form" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Header --}}
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Edit Slider</h2>
                    <p class="text-gray-600 mt-1">Update slider: {{ $slider->title }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.sliders.index') }}"
                        class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white font-semibold rounded-lg hover:shadow-lg transition-all">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Update Slider
                    </button>
                </div>
            </div>

            {{-- Main Form Card --}}
            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-6 space-y-6">
                    {{-- Images Upload --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Images</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Desktop Image --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Desktop Image <span class="text-red-500">*</span>
                                </label>

                                {{-- Current Image --}}
                                @if ($slider->image)
                                    <div class="mb-3">
                                        <p class="text-xs text-gray-600 mb-2">Current Image:</p>
                                        <img src="{{ $slider->image_url }}" alt="Current desktop image"
                                            class="w-full h-32 object-cover rounded-lg border border-gray-200">
                                    </div>
                                @endif

                                {{-- Upload New --}}
                                <div
                                    class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-[#0053C5] transition-colors">
                                    <input type="file" id="image" name="image" accept="image/*" class="hidden"
                                        onchange="previewImage(event, 'desktop-preview')">
                                    <label for="image" class="cursor-pointer">
                                        <div id="desktop-preview">
                                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="text-sm text-gray-600">Click to upload new desktop image</p>
                                            <p class="text-xs text-gray-500 mt-1">Recommended: 1920x600px</p>
                                        </div>
                                    </label>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Leave empty to keep current image</p>
                                @error('image')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Mobile Image --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Mobile Image <span class="text-gray-500 text-xs">(Optional)</span>
                                </label>

                                {{-- Current Image --}}
                                @if ($slider->image_mobile)
                                    <div class="mb-3">
                                        <p class="text-xs text-gray-600 mb-2">Current Mobile Image:</p>
                                        <img src="{{ $slider->image_mobile_url }}" alt="Current mobile image"
                                            class="w-full h-32 object-cover rounded-lg border border-gray-200">
                                    </div>
                                @endif

                                {{-- Upload New --}}
                                <div
                                    class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-[#0053C5] transition-colors">
                                    <input type="file" id="image_mobile" name="image_mobile" accept="image/*"
                                        class="hidden" onchange="previewImage(event, 'mobile-preview')">
                                    <label for="image_mobile" class="cursor-pointer">
                                        <div id="mobile-preview">
                                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                            <p class="text-sm text-gray-600">Click to upload new mobile image</p>
                                            <p class="text-xs text-gray-500 mt-1">Recommended: 768x600px</p>
                                        </div>
                                    </label>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Leave empty to keep current image</p>
                                @error('image_mobile')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Content</h3>

                        <div class="space-y-4">
                            {{-- Title --}}
                            <div>
                                <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="title" name="title"
                                    value="{{ old('title', $slider->title) }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('title') border-red-500 @enderror"
                                    placeholder="Enter slider title">
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Subtitle --}}
                            <div>
                                <label for="subtitle" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Subtitle
                                </label>
                                <input type="text" id="subtitle" name="subtitle"
                                    value="{{ old('subtitle', $slider->subtitle) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                                    placeholder="Enter subtitle (optional)">
                            </div>

                            {{-- Description --}}
                            <div>
                                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Description
                                </label>
                                <textarea id="description" name="description" rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                                    placeholder="Enter description (optional)">{{ old('description', $slider->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Button Settings --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Call-to-Action Button</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- Button Text --}}
                            <div>
                                <label for="button_text" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Button Text
                                </label>
                                <input type="text" id="button_text" name="button_text"
                                    value="{{ old('button_text', $slider->button_text) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                                    placeholder="e.g., Learn More">
                            </div>

                            {{-- Button URL --}}
                            <div>
                                <label for="button_url" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Button URL
                                </label>
                                <input type="url" id="button_url" name="button_url"
                                    value="{{ old('button_url', $slider->button_url) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                                    placeholder="https://...">
                            </div>

                            {{-- Button Style --}}
                            <div>
                                <label for="button_style" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Button Style
                                </label>
                                <select id="button_style" name="button_style"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                    @foreach ($buttonStyles as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('button_style', $slider->button_style) == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Display Settings --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Display Settings</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Text Position --}}
                            <div>
                                <label for="text_position" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Text Position
                                </label>
                                <select id="text_position" name="text_position"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                    @foreach ($textPositions as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('text_position', $slider->text_position) == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Placement --}}
                            <div>
                                <label for="placement" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Placement <span class="text-red-500">*</span>
                                </label>
                                <select id="placement" name="placement" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('placement') border-red-500 @enderror">
                                    @foreach ($placements as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('placement', $slider->placement) == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('placement')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Overlay Color --}}
                            <div>
                                <label for="overlay_color" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Overlay Color
                                </label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" id="overlay_color" name="overlay_color"
                                        value="{{ old('overlay_color', $slider->overlay_color ?? '#000000') }}"
                                        class="h-12 w-20 border border-gray-300 rounded cursor-pointer">
                                    <input type="text" id="overlay_color_text"
                                        value="{{ old('overlay_color', $slider->overlay_color ?? '#000000') }}"
                                        class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                                        placeholder="#000000" readonly>
                                </div>
                            </div>

                            {{-- Overlay Opacity --}}
                            <div>
                                <label for="overlay_opacity" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Overlay Opacity: <span
                                        id="opacity-value">{{ old('overlay_opacity', $slider->overlay_opacity) }}%</span>
                                </label>
                                <input type="range" id="overlay_opacity" name="overlay_opacity" min="0"
                                    max="100" value="{{ old('overlay_opacity', $slider->overlay_opacity) }}"
                                    class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                                    oninput="document.getElementById('opacity-value').textContent = this.value + '%'">
                            </div>

                            {{-- Display Order --}}
                            <div>
                                <label for="order" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Display Order
                                </label>
                                <input type="number" id="order" name="order"
                                    value="{{ old('order', $slider->order) }}" min="0"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                <p class="mt-1 text-xs text-gray-500">Lower numbers appear first</p>
                            </div>

                            {{-- Animation Effect --}}
                            <div>
                                <label for="animation_effect" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Animation Effect
                                </label>
                                <select id="animation_effect" name="animation_effect"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                    <option value="" {{ !$slider->animation_effect ? 'selected' : '' }}>None
                                    </option>
                                    <option value="fade" {{ $slider->animation_effect == 'fade' ? 'selected' : '' }}>
                                        Fade
                                    </option>
                                    <option value="slide" {{ $slider->animation_effect == 'slide' ? 'selected' : '' }}>
                                        Slide</option>
                                    <option value="zoom" {{ $slider->animation_effect == 'zoom' ? 'selected' : '' }}>
                                        Zoom
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Schedule Settings --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Schedule Settings</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Active From --}}
                            <div>
                                <label for="active_from" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Active From
                                </label>
                                <input type="datetime-local" id="active_from" name="active_from"
                                    value="{{ old('active_from', $slider->active_from?->format('Y-m-d\TH:i')) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                <p class="mt-1 text-xs text-gray-500">Leave empty for immediate activation</p>
                            </div>

                            {{-- Active Until --}}
                            <div>
                                <label for="active_until" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Active Until
                                </label>
                                <input type="datetime-local" id="active_until" name="active_until"
                                    value="{{ old('active_until', $slider->active_until?->format('Y-m-d\TH:i')) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                <p class="mt-1 text-xs text-gray-500">Leave empty for no expiration</p>
                            </div>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="flex items-start">
                            <input type="checkbox" name="is_active" value="1"
                                {{ old('is_active', $slider->is_active) ? 'checked' : '' }}
                                class="w-5 h-5 text-[#0053C5] border-gray-300 rounded focus:ring-[#0053C5] mt-0.5">
                            <span class="ml-3">
                                <span class="block text-sm font-semibold text-gray-700">Active</span>
                                <span class="block text-xs text-gray-500">Enable this slider to display on the
                                    website</span>
                            </span>
                        </label>
                    </div>

                    {{-- Metadata --}}
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Slider Information</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Created:</span>
                                <span class="font-semibold text-gray-900">
                                    {{ $slider->created_at->format('d M Y, H:i') }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-600">Last Updated:</span>
                                <span class="font-semibold text-gray-900">
                                    {{ $slider->updated_at->format('d M Y, H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Footer --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-xl flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        {{-- Delete Button (STANDALONE - NOT IN FORM) --}}
                        <button type="button" onclick="deleteSlider()"
                            class="px-6 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors">
                            Delete Slider
                        </button>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.sliders.index') }}"
                            class="px-6 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white font-semibold rounded-lg hover:shadow-lg transition-all">
                            Update Slider
                        </button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Hidden Delete Form (OUTSIDE main form) --}}
        <form id="delete-form" action="{{ route('admin.sliders.destroy', $slider) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // Preview Image
        function previewImage(event, previewId) {
            const file = event.target.files[0];
            const preview = document.getElementById(previewId);

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" class="max-h-48 mx-auto rounded-lg">`;
                }
                reader.readAsDataURL(file);
            }
        }

        // Sync color picker with text input
        document.getElementById('overlay_color').addEventListener('input', function(e) {
            document.getElementById('overlay_color_text').value = e.target.value;
        });

        // Delete slider function
        function deleteSlider() {
            if (confirm('Are you sure you want to delete this slider? This action cannot be undone.')) {
                document.getElementById('delete-form').submit();
            }
        }
    </script>
@endpush
