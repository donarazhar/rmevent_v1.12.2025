@extends('admin.layouts.app')

@section('title', 'Detail Feedback')

@section('content')
    <div class="container mx-auto px-4 py-6">
        {{-- Header --}}
        <div class="mb-6">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('admin.feedbacks.index') }}" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Detail Feedback</h1>
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
                {{-- Feedback Details --}}
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h2 class="text-xl font-semibold text-gray-900 mb-2">{{ $feedback->subject }}</h2>
                                <div class="flex flex-wrap gap-2">
                                    @php
                                        $typeColors = [
                                            'general' => 'bg-gray-100 text-gray-800',
                                            'event' => 'bg-blue-100 text-blue-800',
                                            'testimonial' => 'bg-green-100 text-green-800',
                                            'complaint' => 'bg-red-100 text-red-800',
                                            'suggestion' => 'bg-purple-100 text-purple-800',
                                        ];
                                        $statusColors = [
                                            'new' => 'bg-yellow-100 text-yellow-800',
                                            'in_review' => 'bg-blue-100 text-blue-800',
                                            'responded' => 'bg-green-100 text-green-800',
                                            'resolved' => 'bg-gray-100 text-gray-800',
                                            'archived' => 'bg-gray-100 text-gray-600',
                                        ];
                                    @endphp
                                    <span
                                        class="px-3 py-1 text-sm font-medium rounded-full {{ $typeColors[$feedback->type] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($feedback->type) }}
                                    </span>
                                    <span
                                        class="px-3 py-1 text-sm font-medium rounded-full {{ $statusColors[$feedback->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst(str_replace('_', ' ', $feedback->status)) }}
                                    </span>
                                    @if ($feedback->is_published)
                                        <span
                                            class="px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">
                                            Published
                                        </span>
                                    @endif
                                    @if ($feedback->is_featured)
                                        <span
                                            class="px-3 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800">
                                            ⭐ Featured
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        {{-- Message --}}
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Pesan:</h3>
                            <div class="bg-gray-50 rounded-lg p-4 text-gray-700 whitespace-pre-wrap">
                                {{ $feedback->message }}</div>
                        </div>

                        {{-- Ratings --}}
                        @if ($feedback->overall_rating)
                            <div class="mb-6">
                                <h3 class="text-sm font-medium text-gray-700 mb-3">Rating:</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <span class="text-sm text-gray-600">Overall</span>
                                        <div class="flex items-center gap-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="w-5 h-5 {{ $i <= $feedback->overall_rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endfor
                                            <span
                                                class="ml-2 text-sm font-medium text-gray-900">{{ $feedback->overall_rating }}</span>
                                        </div>
                                    </div>

                                    @if ($feedback->content_rating)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <span class="text-sm text-gray-600">Content</span>
                                            <div class="flex items-center gap-1">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <svg class="w-5 h-5 {{ $i <= $feedback->content_rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                @endfor
                                                <span
                                                    class="ml-2 text-sm font-medium text-gray-900">{{ $feedback->content_rating }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($feedback->speaker_rating)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <span class="text-sm text-gray-600">Speaker</span>
                                            <div class="flex items-center gap-1">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <svg class="w-5 h-5 {{ $i <= $feedback->speaker_rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                @endfor
                                                <span
                                                    class="ml-2 text-sm font-medium text-gray-900">{{ $feedback->speaker_rating }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($feedback->venue_rating)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <span class="text-sm text-gray-600">Venue</span>
                                            <div class="flex items-center gap-1">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <svg class="w-5 h-5 {{ $i <= $feedback->venue_rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                @endfor
                                                <span
                                                    class="ml-2 text-sm font-medium text-gray-900">{{ $feedback->venue_rating }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($feedback->organization_rating)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <span class="text-sm text-gray-600">Organization</span>
                                            <div class="flex items-center gap-1">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <svg class="w-5 h-5 {{ $i <= $feedback->organization_rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                @endfor
                                                <span
                                                    class="ml-2 text-sm font-medium text-gray-900">{{ $feedback->organization_rating }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($feedback->recommendation_score)
                                        <div
                                            class="flex items-center justify-between p-3 bg-blue-50 rounded-lg md:col-span-2">
                                            <span class="text-sm text-gray-600">Recommendation Score</span>
                                            <div class="flex items-center">
                                                <span
                                                    class="text-2xl font-bold text-blue-600">{{ $feedback->recommendation_score }}</span>
                                                <span class="text-sm text-gray-500 ml-1">/10</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- Suggestions --}}
                        @if ($feedback->suggestions)
                            <div class="mb-6">
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Saran & Masukan:</h3>
                                <div class="bg-gray-50 rounded-lg p-4 text-gray-700 whitespace-pre-wrap">
                                    {{ $feedback->suggestions }}</div>
                            </div>
                        @endif

                        {{-- Admin Response --}}
                        @if ($feedback->admin_response)
                            <div class="border-t border-gray-200 pt-6">
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Respon Admin:</h3>
                                <div class="bg-blue-50 rounded-lg p-4 text-gray-700 whitespace-pre-wrap">
                                    {{ $feedback->admin_response }}</div>
                                @if ($feedback->respondedBy)
                                    <p class="text-xs text-gray-500 mt-2">
                                        Direspon oleh {{ $feedback->respondedBy->name }} pada
                                        {{ $feedback->responded_at->format('d M Y H:i') }}
                                    </p>
                                @endif
                            </div>
                        @else
                            {{-- Response Form --}}
                            <div class="border-t border-gray-200 pt-6">
                                <h3 class="text-sm font-medium text-gray-700 mb-3">Kirim Respon:</h3>
                                <form action="{{ route('admin.feedbacks.respond', $feedback) }}" method="POST">
                                    @csrf
                                    <textarea name="admin_response" rows="4" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Tulis respon Anda di sini..."></textarea>
                                    @error('admin_response')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <div class="mt-3 flex justify-end">
                                        <button type="submit"
                                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                            Kirim Respon
                                        </button>
                                    </div>
                                </form>
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

                    <div class="space-y-3">
                        <div>
                            <label class="text-sm text-gray-600">Nama</label>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $feedback->name ?? ($feedback->user->name ?? '-') }}</p>
                        </div>

                        <div>
                            <label class="text-sm text-gray-600">Email</label>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $feedback->email ?? ($feedback->user->email ?? '-') }}</p>
                        </div>

                        @if ($feedback->phone)
                            <div>
                                <label class="text-sm text-gray-600">Telepon</label>
                                <p class="text-sm font-medium text-gray-900">{{ $feedback->phone }}</p>
                            </div>
                        @endif

                        @if ($feedback->event)
                            <div>
                                <label class="text-sm text-gray-600">Event</label>
                                <p class="text-sm font-medium text-blue-600">
                                    <a href="{{ route('admin.events.show', $feedback->event) }}" class="hover:underline">
                                        {{ $feedback->event->title }}
                                    </a>
                                </p>
                            </div>
                        @endif

                        @if ($feedback->registration)
                            <div>
                                <label class="text-sm text-gray-600">Registrasi</label>
                                <p class="text-sm font-medium text-blue-600">
                                    <a href="{{ route('admin.registrations.show', $feedback->registration) }}"
                                        class="hover:underline">
                                        {{ $feedback->registration->registration_code }}
                                    </a>
                                </p>
                            </div>
                        @endif

                        <div>
                            <label class="text-sm text-gray-600">Tanggal Kirim</label>
                            <p class="text-sm font-medium text-gray-900">{{ $feedback->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Status Actions --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Status</h3>

                    <form action="{{ route('admin.feedbacks.update-status', $feedback) }}" method="POST"
                        class="mb-4">
                        @csrf
                        @method('PATCH')
                        <select name="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent mb-3">
                            <option value="new" {{ $feedback->status == 'new' ? 'selected' : '' }}>New</option>
                            <option value="in_review" {{ $feedback->status == 'in_review' ? 'selected' : '' }}>In Review
                            </option>
                            <option value="responded" {{ $feedback->status == 'responded' ? 'selected' : '' }}>Responded
                            </option>
                            <option value="resolved" {{ $feedback->status == 'resolved' ? 'selected' : '' }}>Resolved
                            </option>
                            <option value="archived" {{ $feedback->status == 'archived' ? 'selected' : '' }}>Archived
                            </option>
                        </select>
                        <button type="submit"
                            class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            Update Status
                        </button>
                    </form>

                    <div class="space-y-2">
                        @if ($feedback->is_published)
                            <form action="{{ route('admin.feedbacks.unpublish', $feedback) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                                    Unpublish Testimonial
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.feedbacks.publish', $feedback) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                                    Publish as Testimonial
                                </button>
                            </form>
                        @endif

                        <button
                            onclick="toggleFeatured({{ $feedback->id }}, {{ $feedback->is_featured ? 'false' : 'true' }})"
                            class="w-full px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition-colors">
                            {{ $feedback->is_featured ? 'Remove from Featured' : 'Mark as Featured' }}
                        </button>

                        <button onclick="confirmDelete({{ $feedback->id }})"
                            class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                            Hapus Feedback
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    @include('admin.feedbacks.modals.delete')

@endsection

@push('scripts')
    <script>
        function confirmDelete(id) {
            document.getElementById('deleteForm').action = `/admin/feedbacks/${id}`;
            openModal('deleteModal');
        }

        function toggleFeatured(id, isFeatured) {
            if (confirm('Ubah status featured feedback ini?')) {
                fetch(`/admin/feedbacks/${id}/toggle-featured`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            is_featured: isFeatured
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan');
                    });
            }
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
