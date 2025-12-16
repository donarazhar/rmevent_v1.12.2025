@extends('admin.layouts.app')

@section('title', 'Manajemen Users')

@section('content')
    <div x-data="userManager()">
        {{-- Header --}}
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Manajemen Users</h1>
                    <p class="text-sm text-gray-600 mt-1">Kelola pengguna sistem</p>
                </div>
                <a href="{{ route('admin.users.create') }}"
                    class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors flex items-center gap-2 justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah User</span>
                </a>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4 mb-6">
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                <p class="text-xs text-gray-600 mb-1">Total</p>
                <p class="text-xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                <p class="text-xs text-gray-600 mb-1">Admin</p>
                <p class="text-xl font-bold text-purple-600">{{ $stats['admin'] }}</p>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                <p class="text-xs text-gray-600 mb-1">Penasehat</p>
                <p class="text-xl font-bold text-blue-600">{{ $stats['penasehat'] }}</p>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                <p class="text-xs text-gray-600 mb-1">Pengarah</p>
                <p class="text-xl font-bold text-cyan-600">{{ $stats['pengarah'] }}</p>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                <p class="text-xs text-gray-600 mb-1">Pelaksana</p>
                <p class="text-xl font-bold text-amber-600">{{ $stats['pelaksana'] }}</p>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                <p class="text-xs text-gray-600 mb-1">Koordinator</p>
                <p class="text-xl font-bold text-orange-600">{{ $stats['koordinator'] }}</p>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                <p class="text-xs text-gray-600 mb-1">Panitia</p>
                <p class="text-xl font-bold text-indigo-600">{{ $stats['panitia'] }}</p>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                <p class="text-xs text-gray-600 mb-1">Jamaah</p>
                <p class="text-xl font-bold text-green-600">{{ $stats['jamaah'] }}</p>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama, email, telepon, jabatan, unit, atau seksi..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                <div class="w-full sm:w-48">
                    <select name="role"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">Semua Role</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="penasehat" {{ request('role') == 'penasehat' ? 'selected' : '' }}>Penasehat</option>
                        <option value="pengarah" {{ request('role') == 'pengarah' ? 'selected' : '' }}>Pengarah</option>
                        <option value="pelaksana" {{ request('role') == 'pelaksana' ? 'selected' : '' }}>Pelaksana</option>
                        <option value="koordinator" {{ request('role') == 'koordinator' ? 'selected' : '' }}>Koordinator
                        </option>
                        <option value="panitia" {{ request('role') == 'panitia' ? 'selected' : '' }}>Panitia</option>
                        <option value="jamaah" {{ request('role') == 'jamaah' ? 'selected' : '' }}>Jamaah</option>
                    </select>
                </div>
                <div class="w-full sm:w-48">
                    <select name="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended
                        </option>
                    </select>
                </div>
                <button type="submit"
                    class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                    Filter
                </button>
                @if (request()->hasAny(['search', 'role', 'status']))
                    <a href="{{ route('admin.users.index') }}"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Users Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jabatan/Unit/Seksi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Joined</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                                            class="w-10 h-10 rounded-full">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            @if ($user->id === auth()->id())
                                                <span class="text-xs text-blue-600">(You)</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->phone ?? '-' }}</div>
                                    @if ($user->email_verified_at)
                                        <span class="text-xs text-green-600">✓ Verified</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $roleConfig = match ($user->role) {
                                            'admin' => [
                                                'bg' => 'bg-purple-100',
                                                'text' => 'text-purple-700',
                                                'icon' => '👨‍💼',
                                                'label' => 'Admin',
                                            ],
                                            'penasehat' => [
                                                'bg' => 'bg-blue-100',
                                                'text' => 'text-blue-700',
                                                'icon' => '🎓',
                                                'label' => 'Penasehat',
                                            ],
                                            'pengarah' => [
                                                'bg' => 'bg-cyan-100',
                                                'text' => 'text-cyan-700',
                                                'icon' => '👔',
                                                'label' => 'Pengarah',
                                            ],
                                            'pelaksana' => [
                                                'bg' => 'bg-amber-100',
                                                'text' => 'text-amber-700',
                                                'icon' => '⭐',
                                                'label' => 'Pelaksana',
                                            ],
                                            'koordinator' => [
                                                'bg' => 'bg-orange-100',
                                                'text' => 'text-orange-700',
                                                'icon' => '🎯',
                                                'label' => 'Koordinator',
                                            ],
                                            'panitia' => [
                                                'bg' => 'bg-indigo-100',
                                                'text' => 'text-indigo-700',
                                                'icon' => '👥',
                                                'label' => 'Panitia',
                                            ],
                                            'jamaah' => [
                                                'bg' => 'bg-green-100',
                                                'text' => 'text-green-700',
                                                'icon' => '🕌',
                                                'label' => 'Jamaah',
                                            ],
                                            default => [
                                                'bg' => 'bg-gray-100',
                                                'text' => 'text-gray-700',
                                                'icon' => '❓',
                                                'label' => 'Unknown',
                                            ],
                                        };
                                    @endphp
                                    <span
                                        class="px-2 py-1 text-xs font-medium {{ $roleConfig['bg'] }} {{ $roleConfig['text'] }} rounded">
                                        {{ $roleConfig['icon'] }} {{ $roleConfig['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($user->full_position)
                                        <div class="text-xs text-gray-700">{{ $user->full_position }}</div>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($user->status === 'active')
                                        <span
                                            class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded">Active</span>
                                    @elseif($user->status === 'inactive')
                                        <span
                                            class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded">Inactive</span>
                                    @else
                                        <span
                                            class="px-2 py-1 text-xs font-medium bg-red-100 text-red-700 rounded">Suspended</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        {{-- Toggle Status --}}
                                        @if ($user->id !== auth()->id())
                                            <button @click="toggleStatus({{ $user->id }}, '{{ $user->status }}')"
                                                class="text-{{ $user->status === 'active' ? 'yellow' : 'green' }}-600 hover:text-{{ $user->status === 'active' ? 'yellow' : 'green' }}-900"
                                                title="{{ $user->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    @if ($user->status === 'active')
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                        Lanjutkan09.02
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    @endif
                                                </svg>
                                            </button>

                                            {{-- Reset Password --}}
                                            <button @click="resetPassword({{ $user->id }}, '{{ $user->name }}')"
                                                class="text-purple-600 hover:text-purple-900" title="Reset Password">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                                </svg>
                                            </button>
                                        @endif

                                        {{-- Edit --}}
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                            class="text-blue-600 hover:text-blue-900" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        {{-- Delete --}}
                                        @if ($user->id !== auth()->id())
                                            <button @click="deleteUser({{ $user->id }}, '{{ $user->name }}')"
                                                class="text-red-600 hover:text-red-900" title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada user</h3>
                                        <p class="text-gray-500">Belum ada user yang terdaftar</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($users->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function userManager() {
            return {
                async toggleStatus(id, currentStatus) {
                    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
                    if (!confirm(`Ubah status user menjadi ${newStatus}?`)) {
                        return;
                    }

                    try {
                        const response = await fetch(`/admin/users/${id}/toggle-status`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (response.ok) {
                            alert('✓ ' + data.message);
                            window.location.reload();
                        } else {
                            alert('✗ ' + (data.message || 'Gagal mengubah status'));
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('✗ Terjadi kesalahan');
                    }
                },

                async resetPassword(id, name) {
                    if (!confirm(`Reset password untuk user "${name}"?\n\nPassword akan direset ke: password123`)) {
                        return;
                    }

                    try {
                        const response = await fetch(`/admin/users/${id}/reset-password`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (response.ok) {
                            alert('✓ ' + data.message);
                        } else {
                            alert('✗ ' + (data.message || 'Gagal reset password'));
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('✗ Terjadi kesalahan');
                    }
                },

                async deleteUser(id, name) {
                    if (!confirm(
                            `Apakah Anda yakin ingin menghapus user "${name}"?\n\nTindakan ini tidak dapat dibatalkan!`
                        )) {
                        return;
                    }

                    try {
                        const response = await fetch(`/admin/users/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (response.ok) {
                            alert('✓ User berhasil dihapus');
                            window.location.reload();
                        } else {
                            alert('✗ ' + (data.message || 'Gagal menghapus user'));
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('✗ Terjadi kesalahan saat menghapus user');
                    }
                }
            }
        }
    </script>
@endpush
