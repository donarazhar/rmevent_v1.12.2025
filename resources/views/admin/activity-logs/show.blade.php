@extends('admin.layouts.app')

@section('title', 'Detail Activity Log')

@section('content')
    <div class="max-w-4xl mx-auto">
        {{-- Breadcrumb --}}
        <nav class="mb-6">
            <ol class="flex items-center gap-2 text-sm text-gray-600">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-[#0053C5]">Dashboard</a></li>
                <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg></li>
                <li><a href="{{ route('admin.activity-logs.index') }}" class="hover:text-[#0053C5]">Activity Logs</a></li>
                <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg></li>
                <li class="text-gray-900 font-medium">Detail</li>
            </ol>
        </nav>

        {{-- Header --}}
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-700 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <span>Activity Log Detail</span>
                </h1>
                <p class="text-sm text-gray-600 mt-1">Log #{{ $activityLog->id }}</p>
            </div>
            <a href="{{ route('admin.activity-logs.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span class="text-sm font-medium">Kembali</span>
            </a>
        </div>

        {{-- Main Content --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            {{-- Action Badge --}}
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <span
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold
                        {{ $activityLog->action === 'created' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $activityLog->action === 'updated' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $activityLog->action === 'deleted' ? 'bg-red-100 text-red-700' : '' }}
                        {{ $activityLog->action === 'login' ? 'bg-purple-100 text-purple-700' : '' }}
                        {{ $activityLog->action === 'logout' ? 'bg-gray-100 text-gray-700' : '' }}
                        {{ !in_array($activityLog->action, ['created', 'updated', 'deleted', 'login', 'logout']) ? 'bg-gray-100 text-gray-700' : '' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if ($activityLog->action === 'created')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            @elseif($activityLog->action === 'updated')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            @elseif($activityLog->action === 'deleted')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            @endif
                        </svg>
                        {{ strtoupper($activityLog->action) }}
                    </span>
                    <span class="text-sm text-gray-600">
                        {{ $activityLog->created_at->format('d M Y, H:i:s') }}
                    </span>
                </div>
            </div>

            <div class="p-6 space-y-6">
                {{-- User Information --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">User Information</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-[#0053C5] to-[#004AB0] rounded-xl flex items-center justify-center text-white text-lg font-bold">
                                {{ $activityLog->user ? substr($activityLog->user->name, 0, 1) : 'S' }}
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-gray-900">{{ $activityLog->user?->name ?? 'System' }}</div>
                                <div class="text-sm text-gray-600">{{ $activityLog->user?->email ?? 'system@automated' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Description</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-900">{{ $activityLog->description ?? 'No description provided' }}</p>
                    </div>
                </div>

                {{-- Subject Information --}}
                @if ($activityLog->subject_type)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Subject</h3>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Type:</span>
                                <span
                                    class="text-sm font-medium text-gray-900">{{ class_basename($activityLog->subject_type) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">ID:</span>
                                <span class="text-sm font-medium text-gray-900">#{{ $activityLog->subject_id }}</span>
                            </div>
                            @if ($activityLog->subject)
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Status:</span>
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Exists
                                    </span>
                                </div>
                            @else
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Status:</span>
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-red-700">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Deleted
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Properties/Changes --}}
                @if ($activityLog->properties)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Properties</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            @if ($activityLog->changes)
                                <div class="space-y-4">
                                    <h4 class="text-xs font-semibold text-gray-600 uppercase">Changes:</h4>
                                    @foreach ($activityLog->changes as $key => $change)
                                        <div class="border-l-2 border-blue-500 pl-4">
                                            <div class="text-sm font-medium text-gray-900 mb-1">
                                                {{ ucfirst(str_replace('_', ' ', $key)) }}</div>
                                            <div class="grid grid-cols-2 gap-4 text-sm">
                                                <div>
                                                    <span class="text-gray-600">Old:</span>
                                                    <div class="mt-1 p-2 bg-red-50 text-red-700 rounded">
                                                        {{ $change['old'] ?? 'null' }}</div>
                                                </div>
                                                <div>
                                                    <span class="text-gray-600">New:</span>
                                                    <div class="mt-1 p-2 bg-green-50 text-green-700 rounded">
                                                        {{ $change['new'] ?? 'null' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <pre class="text-sm text-gray-700 overflow-x-auto">{{ json_encode($activityLog->properties, JSON_PRETTY_PRINT) }}</pre>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Request Information --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Request Information</h3>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                        <div class="flex justify-between items-start">
                            <span class="text-sm text-gray-600">IP Address:</span>
                            <span
                                class="text-sm font-mono font-medium text-gray-900">{{ $activityLog->ip_address ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-sm text-gray-600">User Agent:</span>
                            <span
                                class="text-sm text-gray-900 text-right max-w-md">{{ Str::limit($activityLog->user_agent ?? '-', 100) }}</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-sm text-gray-600">Timestamp:</span>
                            <span
                                class="text-sm font-medium text-gray-900">{{ $activityLog->created_at->format('Y-m-d H:i:s') }}</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-sm text-gray-600">Time Ago:</span>
                            <span
                                class="text-sm font-medium text-gray-900">{{ $activityLog->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <form action="{{ route('admin.activity-logs.destroy', $activityLog) }}" method="POST"
                        class="flex-1" onsubmit="return confirm('Yakin ingin menghapus log ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full px-4 py-2.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-all font-medium">
                            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete Log
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
