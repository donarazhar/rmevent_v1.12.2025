{{-- resources/views/admin/jobdescs/show.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Detail Job Description')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Job Description</h1>
                <p class="text-sm text-gray-600 mt-1">{{ $jobdesc->title }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.jobdescs.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
                <a href="{{ route('admin.jobdescs.edit', $jobdesc) }}"
                    class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
            </div>
        </div>

        {{-- Stats Overview --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Anggota Ditugaskan</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $jobdesc->assigned_members }}/{{ $jobdesc->required_members }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Tingkat Pemenuhan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $jobdesc->fulfillment_rate }}%</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Estimasi Jam</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $jobdesc->estimated_hours ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Posisi Tersisa</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $jobdesc->remaining_positions }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Information --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Info --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h2>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Kode</p>
                            <p class="text-base font-medium text-gray-900">{{ $jobdesc->code }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            @php
                                $statusColors = [
                                    'draft' => 'bg-gray-100 text-gray-800',
                                    'active' => 'bg-green-100 text-green-800',
                                    'completed' => 'bg-blue-100 text-blue-800',
                                    'archived' => 'bg-gray-100 text-gray-800',
                                ];
                            @endphp
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$jobdesc->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($jobdesc->status) }}
                            </span>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Divisi</p>
                            <p class="text-base font-medium text-gray-900">{{ $jobdesc->structure->name ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Event</p>
                            <p class="text-base font-medium text-gray-900">{{ $jobdesc->event->name ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Beban Kerja</p>
                            @php
                                $workloadColors = [
                                    'ringan' => 'bg-green-100 text-green-800',
                                    'sedang' => 'bg-yellow-100 text-yellow-800',
                                    'berat' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $workloadColors[$jobdesc->workload_level] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($jobdesc->workload_level) }}
                            </span>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Prioritas</p>
                            @php
                                $priorityColors = [
                                    'rendah' => 'bg-blue-100 text-blue-800',
                                    'sedang' => 'bg-yellow-100 text-yellow-800',
                                    'tinggi' => 'bg-orange-100 text-orange-800',
                                    'kritis' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $priorityColors[$jobdesc->priority] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($jobdesc->priority) }}
                            </span>
                        </div>

                        @if ($jobdesc->start_date)
                            <div>
                                <p class="text-sm text-gray-600">Tanggal Mulai</p>
                                <p class="text-base font-medium text-gray-900">
                                    {{ $jobdesc->start_date->format('d M Y') }}
                                </p>
                            </div>
                        @endif

                        @if ($jobdesc->end_date)
                            <div>
                                <p class="text-sm text-gray-600">Tanggal Selesai</p>
                                <p class="text-base font-medium text-gray-900">
                                    {{ $jobdesc->end_date->format('d M Y') }}
                                </p>
                            </div>
                        @endif

                        <div>
                            <p class="text-sm text-gray-600">Dibuat Oleh</p>
                            <p class="text-base font-medium text-gray-900">{{ $jobdesc->creator->name ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Dibuat Pada</p>
                            <p class="text-base font-medium text-gray-900">
                                {{ $jobdesc->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Deskripsi</h2>
                    <div class="prose prose-sm max-w-none">
                        <p class="text-gray-700">{{ $jobdesc->description }}</p>
                    </div>
                </div>

                {{-- Responsibilities --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Tanggung Jawab</h2>
                    <ul class="space-y-2">
                        @foreach ($jobdesc->responsibilities as $responsibility)
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-primary mr-2 mt-0.5 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="text-gray-700">{{ $responsibility }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Requirements --}}
                @if (!empty($jobdesc->requirements))
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Persyaratan</h2>
                        <ul class="space-y-2">
                            @foreach ($jobdesc->requirements as $requirement)
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-yellow-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-gray-700">{{ $requirement }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Skills Required --}}
                @if (!empty($jobdesc->skills_required))
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Keterampilan yang Dibutuhkan</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($jobdesc->skills_required as $skill)
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $skill }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Assignments List --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Daftar Penugasan</h2>
                        @if ($jobdesc->remaining_positions > 0)
                            <button @click="$refs.assignModal.showModal()"
                                class="inline-flex items-center px-4 py-2 bg-primary text-white text-sm rounded-lg hover:bg-primary-dark transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Tugaskan User
                            </button>
                        @endif
                    </div>

                    @if ($jobdesc->assignments->count() > 0)
                        <div class="space-y-3">
                            @foreach ($jobdesc->assignments as $assignment)
                                <div
                                    class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center space-x-4">
                                        <div
                                            class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-semibold text-lg">
                                            {{ substr($assignment->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-900">{{ $assignment->user->name }}
                                            </h3>
                                            <p class="text-sm text-gray-500">{{ $assignment->user->email }}</p>
                                            <div class="flex items-center space-x-3 mt-1">
                                                @php
                                                    $statusColors = [
                                                        'assigned' => 'bg-blue-100 text-blue-800',
                                                        'in_progress' => 'bg-yellow-100 text-yellow-800',
                                                        'completed' => 'bg-green-100 text-green-800',
                                                        'cancelled' => 'bg-red-100 text-red-800',
                                                    ];
                                                @endphp
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $statusColors[$assignment->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $assignment->status)) }}
                                                </span>
                                                @if ($assignment->progress_percentage > 0)
                                                    <span class="text-xs text-gray-500">
                                                        Progress: {{ $assignment->progress_percentage }}%
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        @if (in_array($assignment->status, ['assigned', 'in_progress']))
                                            <form
                                                action="{{ route('admin.jobdescs.unassign', [$jobdesc, $assignment->user]) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin membatalkan penugasan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                    title="Batalkan Penugasan">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <p class="mt-2">Belum ada penugasan</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Progress Card --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Progress Pemenuhan</h3>

                    <div class="space-y-4">
                        <div>
                            <div class="flex items-center justify-between text-sm mb-2">
                                <span class="text-gray-600">Anggota Ditugaskan</span>
                                <span class="font-medium text-gray-900">
                                    {{ $jobdesc->assigned_members }}/{{ $jobdesc->required_members }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-primary h-3 rounded-full transition-all duration-300"
                                    style="width: {{ $jobdesc->fulfillment_rate }}%">
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $jobdesc->fulfillment_rate }}% terpenuhi</p>
                        </div>

                        @if ($jobdesc->remaining_positions > 0)
                            <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-sm text-yellow-800">
                                    <strong>{{ $jobdesc->remaining_positions }}</strong> posisi masih tersedia
                                </p>
                            </div>
                        @else
                            <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                                <p class="text-sm text-green-800">
                                    ✓ Semua posisi sudah terisi
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Timeline Card --}}
                @if ($jobdesc->start_date || $jobdesc->end_date)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h3>

                        <div class="space-y-3">
                            @if ($jobdesc->start_date)
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Mulai</p>
                                        <p class="text-sm text-gray-600">{{ $jobdesc->start_date->format('d M Y') }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($jobdesc->end_date)
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Selesai</p>
                                        <p class="text-sm text-gray-600">{{ $jobdesc->end_date->format('d M Y') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Quick Actions --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>

                    <div class="space-y-2">
                        <a href="{{ route('admin.jobdescs.edit', $jobdesc) }}"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Job Description
                        </a>

                        @if ($jobdesc->remaining_positions > 0)
                            <button @click="$refs.assignModal.showModal()"
                                class="w-full flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                                Tugaskan User Baru
                            </button>
                        @endif

                        <form action="{{ route('admin.jobdescs.destroy', $jobdesc) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus job description ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus Job Description
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Assign User Modal --}}
    <dialog x-ref="assignModal" class="rounded-lg shadow-xl backdrop:bg-black backdrop:bg-opacity-50 w-full max-w-md">
        <div class="bg-white p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Tugaskan User</h3>
                <button @click="$refs.assignModal.close()" class="text-gray-400 hover:text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('admin.jobdescs.assign', $jobdesc) }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih User <span class="text-red-500">*</span>
                    </label>
                    <select name="user_id" id="user_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Pilih User</option>
                        @foreach ($availableUsers as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="expected_completion_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Target Penyelesaian
                    </label>
                    <input type="date" name="expected_completion_date" id="expected_completion_date"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan
                    </label>
                    <textarea name="notes" id="notes" rows="3" placeholder="Catatan tambahan untuk penugasan..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4">
                    <button type="button" @click="$refs.assignModal.close()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                        Tugaskan
                    </button>
                </div>
            </form>
        </div>
    </dialog>
@endsection
