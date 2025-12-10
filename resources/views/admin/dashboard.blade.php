@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
    <span class="text-gray-400">/</span>
    <span class="text-gray-900 font-semibold">Dashboard</span>
@endsection

@section('content')
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        {{-- Total Events --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Events</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $stats['total_events'] ?? 0 }}</h3>
                    <p class="text-xs text-green-600 mt-2">
                        <span class="font-semibold">{{ $stats['active_events'] ?? 0 }}</span> Active
                    </p>
                </div>
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Registrations --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Registrations</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $stats['total_registrations'] ?? 0 }}</h3>
                    <p class="text-xs text-green-600 mt-2">
                        <span class="font-semibold">{{ $stats['confirmed_registrations'] ?? 0 }}</span> Confirmed
                    </p>
                </div>
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Posts --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Posts</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $stats['total_posts'] ?? 0 }}</h3>
                    <p class="text-xs text-green-600 mt-2">
                        <span class="font-semibold">{{ $stats['published_posts'] ?? 0 }}</span> Published
                    </p>
                </div>
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Users --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Users</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $stats['total_users'] ?? 0 }}</h3>
                    <p class="text-xs text-green-600 mt-2">
                        <span class="font-semibold">+{{ $stats['new_users_today'] ?? 0 }}</span> Today
                    </p>
                </div>
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Registrations Chart --}}
        {{-- <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Registrations Overview</h3>
            <canvas id="registrationsChart" height="200"></canvas>
        </div> --}}

        {{-- Events by Category --}}
        {{-- <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Events by Category</h3>
            <canvas id="eventsChart" height="200"></canvas>
        </div> --}}
    </div>

    {{-- Tables Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Registrations --}}
        <div class="bg-white rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">Recent Registrations</h3>
                <a href="{{ route('admin.registrations.index') }}" class="text-sm text-[#0053C5] hover:text-[#003d8f]">
                    View All →
                </a>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentRegistrations ?? [] as $registration)
                    <div class="px-6 py-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">{{ $registration->name }}</p>
                                <p class="text-sm text-gray-600">{{ $registration->event->title ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $registration->created_at->diffForHumans() }}</p>
                            </div>
                            <span
                                class="px-3 py-1 text-xs font-semibold rounded-full
                                {{ $registration->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($registration->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        No recent registrations
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Upcoming Events --}}
        <div class="bg-white rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">Upcoming Events</h3>
                <a href="{{ route('admin.events.index') }}" class="text-sm text-[#0053C5] hover:text-[#003d8f]">
                    View All →
                </a>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($upcomingEvents ?? [] as $event)
                    <div class="px-6 py-4 hover:bg-gray-50">
                        <div class="flex items-start space-x-3">
                            <div
                                class="flex-shrink-0 w-12 h-12 bg-gradient-to-r from-[#0053C5] to-[#003d8f] rounded-lg flex flex-col items-center justify-center text-white">
                                <span class="text-xs font-semibold">{{ $event->start_datetime->format('M') }}</span>
                                <span class="text-lg font-bold">{{ $event->start_datetime->format('d') }}</span>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">{{ $event->title }}</p>
                                <p class="text-sm text-gray-600">{{ $event->start_datetime->format('d M Y, H:i') }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $event->current_participants }}/{{ $event->max_participants }} participants
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        No upcoming events
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Registrations Chart
        const registrationsCtx = document.getElementById('registrationsChart').getContext('2d');
        new Chart(registrationsCtx, {
            type: 'line',
            data: {
                labels: @json($chartData['registrations']['labels'] ?? []),
                datasets: [{
                    label: 'Registrations',
                    data: @json($chartData['registrations']['data'] ?? []),
                    borderColor: '#0053C5',
                    backgroundColor: 'rgba(0, 83, 197, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Events Chart
        const eventsCtx = document.getElementById('eventsChart').getContext('2d');
        new Chart(eventsCtx, {
            type: 'doughnut',
            data: {
                labels: @json($chartData['events']['labels'] ?? []),
                datasets: [{
                    data: @json($chartData['events']['data'] ?? []),
                    backgroundColor: [
                        '#0053C5',
                        '#003d8f',
                        '#3B82F6',
                        '#60A5FA',
                        '#93C5FD',
                        '#BFDBFE',
                        '#DBEAFE',
                        '#EFF6FF'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
@endpush
