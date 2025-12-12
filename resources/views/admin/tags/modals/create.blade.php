{{-- resources/views/admin/tags/modals/create.blade.php --}}
<div id="createModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        {{-- Modal Header --}}
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-900">Tambah Tag Baru</h3>
            <button type="button" onclick="closeModal('createModal')"
                class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Modal Body --}}
        <form method="POST" action="{{ route('admin.tags.store') }}">
            @csrf

            <div class="p-6 space-y-4">
                {{-- Name Field --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Tag <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                        placeholder="Masukkan nama tag">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description Field --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea id="description" name="description" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                        placeholder="Masukkan deskripsi tag (opsional)">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Color Field --}}
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700 mb-2">
                        Warna Tag
                    </label>
                    <div class="flex items-center gap-3">
                        <input type="color" id="color" name="color" value="{{ old('color', '#6B7280') }}"
                            class="h-10 w-20 border border-gray-300 rounded cursor-pointer">
                        <span class="text-sm text-gray-600">Pilih warna untuk tag</span>
                    </div>
                    @error('color')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Color Presets --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Atau pilih warna preset:
                    </label>
                    <div class="grid grid-cols-8 gap-2">
                        @php
                            $presetColors = [
                                '#EF4444',
                                '#F59E0B',
                                '#10B981',
                                '#3B82F6',
                                '#6366F1',
                                '#8B5CF6',
                                '#EC4899',
                                '#14B8A6',
                            ];
                        @endphp
                        @foreach ($presetColors as $presetColor)
                            <button type="button"
                                onclick="document.getElementById('color').value = '{{ $presetColor }}'"
                                class="w-8 h-8 rounded border-2 border-gray-300 hover:border-gray-900 transition-colors"
                                style="background-color: {{ $presetColor }}" title="{{ $presetColor }}">
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-200 bg-gray-50">
                <button type="button" onclick="closeModal('createModal')"
                    class="px-4 py-2 text-gray-700 hover:bg-gray-100 font-medium rounded-lg transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    Simpan Tag
                </button>
            </div>
        </form>
    </div>
</div>

@if ($errors->any() && old('name'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            openModal('createModal');
        });
    </script>
@endif
