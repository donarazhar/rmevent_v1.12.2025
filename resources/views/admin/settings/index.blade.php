@extends('admin.layouts.app')

@section('title', 'Pengaturan Aplikasi')

@section('content')
    <div x-data="{
        activeTab: 'general',
        saving: false
    }">
        {{-- Page Header --}}
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-[#0053C5] to-[#004AB0] rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span>Pengaturan Aplikasi</span>
                    </h1>
                    <p class="text-sm text-gray-600 mt-1">Kelola konfigurasi dan pengaturan sistem</p>
                </div>
                <div>
                    <form action="{{ route('admin.settings.clear-cache') }}" method="POST" class="inline"
                        onsubmit="return confirm('Yakin ingin menghapus semua cache?')">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span class="text-sm font-medium">Clear Cache</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            {{-- Sidebar Tabs --}}
            <div class="lg:col-span-3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-6">
                    <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-[#0053C5] to-[#004AB0]">
                        <h3 class="text-sm font-semibold text-white">Kategori Pengaturan</h3>
                    </div>
                    <nav class="p-2">
                        @foreach ($settings as $group => $groupSettings)
                            <button @click="activeTab = '{{ $group }}'"
                                :class="activeTab === '{{ $group }}' ? 'bg-[#0053C5] text-white' :
                                    'text-gray-700 hover:bg-gray-50'"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 group mb-1">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                                    :class="activeTab === '{{ $group }}' ? 'bg-white/20' :
                                        'bg-gray-100 group-hover:bg-gray-200'">
                                    <svg class="w-4 h-4"
                                        :class="activeTab === '{{ $group }}' ? 'text-white' : 'text-gray-600'"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        {!! getGroupIconSvg($group) !!}
                                    </svg>
                                </div>
                                <div class="flex-1 text-left">
                                    <div class="text-sm font-medium">{{ ucwords(str_replace('_', ' ', $group)) }}</div>
                                    <div class="text-xs opacity-75">{{ $groupSettings->count() }} pengaturan</div>
                                </div>
                            </button>
                        @endforeach
                    </nav>
                </div>
            </div>

            {{-- Content Area --}}
            <div class="lg:col-span-9">
                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data"
                    @submit="saving = true">
                    @csrf
                    @method('PUT')

                    @foreach ($settings as $group => $groupSettings)
                        <div x-show="activeTab === '{{ $group }}'"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform translate-y-4"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            class="bg-white rounded-xl shadow-sm border border-gray-200">

                            {{-- Card Header --}}
                            <div class="p-6 border-b border-gray-200">
                                <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#0053C5] to-[#004AB0] flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            {!! getGroupIconSvg($group) !!}
                                        </svg>
                                    </div>
                                    {{ ucwords(str_replace('_', ' ', $group)) }}
                                </h2>
                                <p class="text-sm text-gray-600 mt-1">Konfigurasi pengaturan
                                    {{ strtolower(str_replace('_', ' ', $group)) }}</p>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @foreach ($groupSettings as $setting)
                                        <div
                                            class="{{ in_array($setting->type, ['textarea', 'json']) ? 'md:col-span-2' : '' }}">
                                            <label for="{{ $setting->key }}"
                                                class="block text-sm font-semibold text-gray-700 mb-2">
                                                {{ formatLabel($setting->key) }}
                                                @if ($setting->description)
                                                    <span x-data="{ tooltip: false }" class="relative inline-block ml-1">
                                                        <button type="button" @mouseenter="tooltip = true"
                                                            @mouseleave="tooltip = false"
                                                            class="text-gray-400 hover:text-gray-600">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </button>
                                                        <div x-show="tooltip" x-transition
                                                            class="absolute z-10 px-3 py-2 text-xs text-white bg-gray-900 rounded-lg shadow-lg -top-2 left-6 w-64">
                                                            {{ $setting->description }}
                                                        </div>
                                                    </span>
                                                @endif
                                            </label>

                                            @switch($setting->type)
                                                @case('text')
                                                    <input type="text" id="{{ $setting->key }}" name="{{ $setting->key }}"
                                                        value="{{ old($setting->key, $setting->value) }}"
                                                        placeholder="{{ $setting->description }}"
                                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent transition-all">
                                                @break

                                                @case('textarea')
                                                    <textarea id="{{ $setting->key }}" name="{{ $setting->key }}" rows="4"
                                                        placeholder="{{ $setting->description }}"
                                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent transition-all resize-none">{{ old($setting->key, $setting->value) }}</textarea>
                                                @break

                                                @case('image')
                                                    <div class="space-y-3">
                                                        @if ($setting->value)
                                                            <div class="relative inline-block">
                                                                <img src="{{ asset('storage/' . $setting->value) }}"
                                                                    alt="{{ $setting->key }}"
                                                                    class="h-32 rounded-lg border-2 border-gray-200 object-cover">
                                                                <div class="absolute -top-2 -right-2">
                                                                    <span
                                                                        class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                stroke-width="2" d="M5 13l4 4L19 7" />
                                                                        </svg>
                                                                        Current
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="flex items-center justify-center w-full">
                                                            <label for="{{ $setting->key }}"
                                                                class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                                    <svg class="w-8 h-8 mb-2 text-gray-400" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                                    </svg>
                                                                    <p class="mb-1 text-sm text-gray-500"><span
                                                                            class="font-semibold">Click to upload</span></p>
                                                                    <p class="text-xs text-gray-500">PNG, JPG (MAX. 2MB)</p>
                                                                </div>
                                                                <input type="file" id="{{ $setting->key }}"
                                                                    name="{{ $setting->key }}" accept="image/*" class="hidden">
                                                            </label>
                                                        </div>
                                                    </div>
                                                @break

                                                @case('boolean')
                                                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
                                                        <input type="hidden" name="{{ $setting->key }}" value="0">
                                                        <button type="button"
                                                            @click="$el.nextElementSibling.checked = !$el.nextElementSibling.checked"
                                                            :class="$el.nextElementSibling.checked ? 'bg-[#0053C5]' :
                                                                'bg-gray-300'"
                                                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors">
                                                            <span
                                                                :class="$el.parentElement.querySelector('input[type=checkbox]')
                                                                    .checked ? 'translate-x-6' : 'translate-x-1'"
                                                                class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
                                                        </button>
                                                        <input type="checkbox" id="{{ $setting->key }}"
                                                            name="{{ $setting->key }}" value="1"
                                                            {{ old($setting->key, $setting->value) ? 'checked' : '' }}
                                                            class="hidden">
                                                        <label for="{{ $setting->key }}" class="text-sm text-gray-700">
                                                            {{ $setting->description ?: 'Aktifkan fitur ini' }}
                                                        </label>
                                                    </div>
                                                @break

                                                @case('color')
                                                    <div class="flex gap-3">
                                                        <input type="color" id="{{ $setting->key }}_picker"
                                                            value="{{ old($setting->key, $setting->value ?? '#0053C5') }}"
                                                            @input="document.getElementById('{{ $setting->key }}').value = $event.target.value"
                                                            class="h-11 w-16 rounded-lg border border-gray-300 cursor-pointer">
                                                        <input type="text" id="{{ $setting->key }}"
                                                            name="{{ $setting->key }}"
                                                            value="{{ old($setting->key, $setting->value ?? '#0053C5') }}"
                                                            @input="document.getElementById('{{ $setting->key }}_picker').value = $event.target.value"
                                                            placeholder="#000000" maxlength="7"
                                                            class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent transition-all font-mono">
                                                    </div>
                                                @break

                                                @case('date')
                                                    <input type="date" id="{{ $setting->key }}" name="{{ $setting->key }}"
                                                        value="{{ old($setting->key, $setting->value) }}"
                                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent transition-all">
                                                @break

                                                @case('json')
                                                    <textarea id="{{ $setting->key }}" name="{{ $setting->key }}" rows="6" placeholder='{"key": "value"}'
                                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent transition-all font-mono text-sm resize-none">{{ old($setting->key, is_array($setting->value) ? json_encode($setting->value, JSON_PRETTY_PRINT) : $setting->value) }}</textarea>
                                                @break
                                            @endswitch

                                            @error($setting->key)
                                                <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- Submit Button (Sticky) --}}
                    <div class="sticky bottom-0 mt-6 bg-white rounded-xl shadow-lg border border-gray-200 p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Perubahan akan langsung diterapkan setelah disimpan</span>
                            </div>
                            <button type="submit" :disabled="saving"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#0053C5] to-[#004AB0] text-white rounded-xl hover:shadow-lg hover:scale-105 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg x-show="!saving" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                <svg x-show="saving" class="w-5 h-5 animate-spin" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span x-text="saving ? 'Menyimpan...' : 'Simpan Pengaturan'" class="font-semibold"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@php
    function getGroupIconSvg($group)
    {
        return match ($group) {
            'general'
                => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />',
            'appearance'
                => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />',
            'social'
                => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />',
            'seo'
                => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />',
            'features'
                => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />',
            'email'
                => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />',
            'payment'
                => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />',
            'notification'
                => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />',
            'security'
                => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />',
            default
                => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />',
        };
    }

    function formatLabel($key)
    {
        return ucwords(str_replace(['_', '-'], ' ', $key));
    }
@endphp
