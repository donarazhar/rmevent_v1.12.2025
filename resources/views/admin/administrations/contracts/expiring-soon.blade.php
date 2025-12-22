@extends('admin.layouts.app')

@section('title', 'Kontrak Segera Berakhir')

@section('content')
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.contracts.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Kontrak Segera Berakhir</h1>
                    <p class="text-gray-600 mt-1">Daftar kontrak yang akan berakhir dalam {{ $days }} hari</p>
                </div>
            </div>

            {{-- Filter Days --}}
            <form method="GET" action="{{ route('admin.contracts.expiring-soon') }}" class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700">Dalam</label>
                <select name="days" onchange="this.form.submit()"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                    <option value="7" {{ $days == 7 ? 'selected' : '' }}>7 hari</option>
                    <option value="14" {{ $days == 14 ? 'selected' : '' }}>14 hari</option>
                    <option value="30" {{ $days == 30 ? 'selected' : '' }}>30 hari</option>
                    <option value="60" {{ $days == 60 ? 'selected' : '' }}>60 hari</option>
                    <option value="90" {{ $days == 90 ? 'selected' : '' }}>90 hari</option>
                </select>
            </form>
        </div>

        {{-- Alert --}}
        @if ($contracts->count() > 0)
            <div class="bg-orange-50 border border-orange-200 rounded-xl p-5">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-orange-600 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <h3 class="font-semibold text-orange-900">Perhatian!</h3>
                        <p class="text-sm text-orange-800 mt-1">
                            Terdapat <strong>{{ $contracts->count() }} kontrak</strong> yang akan berakhir dalam
                            {{ $days }} hari ke depan.
                            Segera lakukan tindakan yang diperlukan untuk perpanjangan atau penyelesaian kontrak.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Contracts List --}}
        <div class="space-y-4">
            @forelse($contracts as $contract)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-start justify-between gap-4">
                            {{-- Contract Info --}}
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $contract->contract_code }}</h3>
                                    <span
                                        class="px-2.5 py-1 rounded-full text-xs font-medium
                                    {{ $contract->days_remaining <= 7 ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $contract->days_remaining > 7 && $contract->days_remaining <= 14 ? 'bg-orange-100 text-orange-800' : '' }}
                                    {{ $contract->days_remaining > 14 ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                        {{ $contract->days_remaining }} hari lagi
                                    </span>
                                </div>

                                <p class="text-gray-900 font-medium mb-1">{{ $contract->title }}</p>

                                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mt-3">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <span>{{ $contract->party_b_name }}</span>
                                    </div>

                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="capitalize">{{ $contract->type }}</span>
                                    </div>

                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>{{ $contract->contract_value_formatted }}</span>
                                    </div>
                                </div>

                                {{-- Timeline --}}
                                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                    <div class="grid grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <p class="text-gray-600 mb-1">Tanggal Mulai</p>
                                            <p class="font-medium text-gray-900">
                                                {{ $contract->start_date->format('d M Y') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-600 mb-1">Tanggal Berakhir</p>
                                            <p class="font-medium text-red-600">{{ $contract->end_date->format('d M Y') }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-gray-600 mb-1">Durasi Total</p>
                                            <p class="font-medium text-gray-900">{{ $contract->duration_days }} hari</p>
                                        </div>
                                    </div>

                                    {{-- Progress Bar --}}
                                    @php
                                        $totalDays = $contract->duration_days;
                                        $elapsed = $contract->start_date->diffInDays(now());
                                        $progress = ($elapsed / $totalDays) * 100;
                                        $progress = min(100, max(0, $progress));
                                    @endphp
                                    <div class="mt-3">
                                        <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                                            <span>Progress</span>
                                            <span>{{ number_format($progress, 1) }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="h-2 rounded-full transition-all duration-300
                                            {{ $progress >= 90 ? 'bg-red-600' : '' }}
                                            {{ $progress >= 70 && $progress < 90 ? 'bg-orange-500' : '' }}
                                            {{ $progress < 70 ? 'bg-green-500' : '' }}"
                                                style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>
                                </div>

                                {{-- PIC Info --}}
                                @if ($contract->picInternal)
                                    <div class="mt-3 flex items-center gap-2 text-sm text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span>PIC: {{ $contract->picInternal->name }}</span>
                                    </div>
                                @endif

                                @if ($contract->event)
                                    <div class="mt-2">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700">
                                            Event: {{ $contract->event->name }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex flex-col gap-2">
                                <a href="{{ route('admin.contracts.show', $contract) }}"
                                    class="inline-flex items-center justify-center px-4 py-2 bg-[#0053C5] hover:bg-[#004AB0] text-white rounded-lg transition-colors text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Lihat Detail
                                </a>

                                <form action="{{ route('admin.contracts.renew', $contract) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin memperpanjang kontrak ini?')">
                                    @csrf
                                    <input type="hidden" name="start_date"
                                        value="{{ $contract->end_date->addDay()->format('Y-m-d') }}">
                                    <input type="hidden" name="end_date"
                                        value="{{ $contract->end_date->addYear()->format('Y-m-d') }}">
                                    <button type="submit"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Perpanjang
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-green-500 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak Ada Kontrak yang Akan Berakhir</h3>
                    <p class="text-gray-600">
                        Tidak ada kontrak yang akan berakhir dalam {{ $days }} hari ke depan.
                    </p>
                    <a href="{{ route('admin.contracts.index') }}"
                        class="inline-flex items-center px-4 py-2 mt-4 bg-[#0053C5] hover:bg-[#004AB0] text-white rounded-lg transition-colors">
                        Kembali ke Daftar Kontrak
                    </a>
                </div>
            @endforelse
        </div>
    </div>
@endsection
