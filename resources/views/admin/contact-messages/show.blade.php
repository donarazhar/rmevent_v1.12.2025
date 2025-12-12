@extends('admin.layouts.app')

@section('title', 'Detail Pesan Kontak')

@section('content')
    <div class="container mx-auto px-4 py-6">
        {{-- Header --}}
        <div class="mb-6">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('admin.contact-messages.index') }}" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Detail Pesan Kontak</h1>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div
                class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Message Details --}}
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h2 class="text-xl font-semibold text-gray-900 mb-2">{{ $contactMessage->subject }}</h2>
                                <div class="flex flex-wrap gap-2">
                                    @php
                                        $typeColors = [
                                            'general' => 'bg-gray-100 text-gray-800',
                                            'event_inquiry' => 'bg-blue-100 text-blue-800',
                                            'complaint' => 'bg-red-100 text-red-800',
                                            'suggestion' => 'bg-purple-100 text-purple-800',
                                            'partnership' => 'bg-green-100 text-green-800',
                                        ];
                                        $statusColors = [
                                            'new' => 'bg-yellow-100 text-yellow-800',
                                            'in_progress' => 'bg-blue-100 text-blue-800',
                                            'replied' => 'bg-green-100 text-green-800',
                                            'resolved' => 'bg-gray-100 text-gray-800',
                                            'archived' => 'bg-gray-100 text-gray-600',
                                        ];
                                    @endphp
                                    <span
                                        class="px-3 py-1 text-sm font-medium rounded-full {{ $typeColors[$contactMessage->type] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst(str_replace('_', ' ', $contactMessage->type)) }}
                                    </span>
                                    <span
                                        class="px-3 py-1 text-sm font-medium rounded-full {{ $statusColors[$contactMessage->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst(str_replace('_', ' ', $contactMessage->status)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        {{-- Original Message --}}
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Pesan:</h3>
                            <div
                                class="bg-gray-50 rounded-lg p-4 text-gray-700 whitespace-pre-wrap border-l-4 border-blue-500">
                                {{ $contactMessage->message }}</div>
                        </div>

                        {{-- Admin Reply --}}
                        @if ($contactMessage->admin_reply)
                            <div class="border-t border-gray-200 pt-6">
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Balasan Admin:</h3>
                                <div
                                    class="bg-green-50 rounded-lg p-4 text-gray-700 whitespace-pre-wrap border-l-4 border-green-500">
                                    {{ $contactMessage->admin_reply }}</div>
                                @if ($contactMessage->repliedBy)
                                    <div class="mt-3 flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Dibalas oleh {{ $contactMessage->repliedBy->name }} pada
                                        {{ $contactMessage->replied_at->format('d M Y H:i') }}
                                    </div>
                                @endif
                            </div>
                        @else
                            {{-- Reply Form --}}
                            <div class="border-t border-gray-200 pt-6">
                                <h3 class="text-sm font-medium text-gray-700 mb-3">Kirim Balasan:</h3>
                                <form action="{{ route('admin.contact-messages.reply', $contactMessage) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <textarea name="admin_reply" rows="6" required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="Tulis balasan Anda di sini...">Halo {{ $contactMessage->name }},

Terima kasih telah menghubungi kami. 

</textarea>
                                        @error('admin_reply')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="flex items-center justify-between bg-blue-50 rounded-lg p-3 mb-3">
                                        <div class="flex items-center text-sm text-blue-700">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>Balasan akan dikirim ke:
                                                <strong>{{ $contactMessage->email }}</strong></span>
                                        </div>
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="submit"
                                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            Kirim Balasan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Activity Timeline --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Timeline Aktivitas</h3>
                    <div class="space-y-4">
                        {{-- Created --}}
                        <div class="flex gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Pesan Diterima</p>
                                <p class="text-xs text-gray-500">{{ $contactMessage->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>

                        {{-- Reply --}}
                        @if ($contactMessage->replied_at)
                            <div class="flex gap-3">
                                <div
                                    class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Pesan Dibalas</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $contactMessage->replied_at->format('d M Y H:i') }}</p>
                                    @if ($contactMessage->repliedBy)
                                        <p class="text-xs text-gray-400">oleh {{ $contactMessage->repliedBy->name }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- Resolved --}}
                        @if ($contactMessage->isResolved())
                            <div class="flex gap-3">
                                <div
                                    class="flex-shrink-0 w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Ditandai Resolved</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $contactMessage->updated_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Sender Info --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pengirim</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="text-xs text-gray-500 uppercase tracking-wider">Nama</label>
                            <p class="text-sm font-medium text-gray-900">{{ $contactMessage->name }}</p>
                        </div>

                        <div>
                            <label class="text-xs text-gray-500 uppercase tracking-wider">Email</label>
                            <a href="mailto:{{ $contactMessage->email }}"
                                class="text-sm font-medium text-blue-600 hover:underline">
                                {{ $contactMessage->email }}
                            </a>
                        </div>

                        @if ($contactMessage->phone)
                            <div>
                                <label class="text-xs text-gray-500 uppercase tracking-wider">Telepon</label>
                                <a href="tel:{{ $contactMessage->phone }}"
                                    class="text-sm font-medium text-blue-600 hover:underline">
                                    {{ $contactMessage->phone }}
                                </a>
                            </div>
                        @endif

                        <div>
                            <label class="text-xs text-gray-500 uppercase tracking-wider">Tanggal Kirim</label>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $contactMessage->created_at->format('d M Y H:i') }}</p>
                            <p class="text-xs text-gray-400">{{ $contactMessage->created_at->diffForHumans() }}</p>
                        </div>

                        @if ($contactMessage->ip_address)
                            <div>
                                <label class="text-xs text-gray-500 uppercase tracking-wider">IP Address</label>
                                <p class="text-xs font-mono text-gray-600">{{ $contactMessage->ip_address }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>

                    <div class="space-y-2">
                        {{-- Email --}}
                        <a href="mailto:{{ $contactMessage->email }}?subject=Re: {{ $contactMessage->subject }}"
                            class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Balas via Email Client
                        </a>

                        @if ($contactMessage->phone)
                            <a href="tel:{{ $contactMessage->phone }}"
                                class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                Hubungi via Telepon
                            </a>
                        @endif

                        {{-- Mark as Resolved --}}
                        @if (!$contactMessage->isResolved())
                            <form action="{{ route('admin.contact-messages.mark-resolved', $contactMessage) }}"
                                method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Tandai Resolved
                                </button>
                            </form>
                        @endif

                        {{-- Archive --}}
                        <form action="{{ route('admin.contact-messages.archive', $contactMessage) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                                Arsipkan
                            </button>
                        </form>

                        {{-- Delete --}}
                        <button onclick="confirmDelete({{ $contactMessage->id }})"
                            class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus Pesan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    @include('admin.contact-messages.modals.delete')

@endsection

@push('scripts')
    <script>
        function confirmDelete(id) {
            document.getElementById('deleteForm').action = `/admin/contact-messages/${id}`;
            openModal('deleteModal');
        }

        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.querySelectorAll('[id$="Modal"]').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal(this.id);
                }
            });
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('[id$="Modal"]').forEach(modal => {
                    if (!modal.classList.contains('hidden')) {
                        closeModal(modal.id);
                    }
                });
            }
        });
    </script>
@endpush
