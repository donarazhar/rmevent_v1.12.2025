@extends('admin.layouts.app')

@section('title', 'Milestone Kanban Board')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Milestone Kanban Board</h1>
                <p class="text-sm text-gray-600 mt-1">Kelola milestone dengan tampilan kanban</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.milestone.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    List View
                </a>
                <a href="{{ route('admin.milestone.timeline') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Timeline View
                </a>
                <button @click="window.dispatchEvent(new CustomEvent('open-modal', {detail: 'create-milestone'}))"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors text-sm font-medium shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Milestone
                </button>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <form method="GET" action="{{ route('admin.milestone.kanban') }}" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Event</label>
                        <select name="event_id"
                            class="w-full text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm"
                            onchange="this.form.submit()">
                            <option value="">Semua Event</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}"
                                    {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                    {{ $event->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                        <select name="priority"
                            class="w-full text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm"
                            onchange="this.form.submit()">
                            <option value="">Semua Priority</option>
                            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="critical" {{ request('priority') == 'critical' ? 'selected' : '' }}>Critical
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">PIC</label>
                        <select name="responsible_person"
                            class="w-full text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm"
                            onchange="this.form.submit()">
                            <option value="">Semua PIC</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ request('responsible_person') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>

        {{-- Kanban Board --}}
        <div class="overflow-x-auto pb-4">
            <div class="inline-flex gap-4 min-w-full">
                {{-- Pending Column --}}
                <div class="flex-1 min-w-[300px]">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 h-full flex flex-col">
                        <div class="p-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                                    <h3 class="font-semibold text-gray-900">Pending</h3>
                                    <span
                                        class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">{{ $milestones->where('status', 'pending')->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 space-y-3 flex-1 overflow-y-auto max-h-[calc(100vh-300px)]">
                            @forelse($milestones->where('status', 'pending') as $milestone)
                                @include('admin.timeline.milestone.partials.kanban-card', [
                                    'milestone' => $milestone,
                                ])
                            @empty
                                <div class="text-center py-8 text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="text-sm">Tidak ada milestone</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- In Progress Column --}}
                <div class="flex-1 min-w-[300px]">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 h-full flex flex-col">
                        <div class="p-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                    <h3 class="font-semibold text-gray-900">In Progress</h3>
                                    <span
                                        class="px-2 py-0.5 bg-blue-100 text-blue-600 text-xs font-medium rounded-full">{{ $milestones->where('status', 'in_progress')->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 space-y-3 flex-1 overflow-y-auto max-h-[calc(100vh-300px)]">
                            @forelse($milestones->where('status', 'in_progress') as $milestone)
                                @include('admin.timeline.milestone.partials.kanban-card', [
                                    'milestone' => $milestone,
                                ])
                            @empty
                                <div class="text-center py-8 text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="text-sm">Tidak ada milestone</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Completed Column --}}
                <div class="flex-1 min-w-[300px]">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 h-full flex flex-col">
                        <div class="p-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                    <h3 class="font-semibold text-gray-900">Completed</h3>
                                    <span
                                        class="px-2 py-0.5 bg-green-100 text-green-600 text-xs font-medium rounded-full">{{ $milestones->where('status', 'completed')->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 space-y-3 flex-1 overflow-y-auto max-h-[calc(100vh-300px)]">
                            @forelse($milestones->where('status', 'completed') as $milestone)
                                @include('admin.timeline.milestone.partials.kanban-card', [
                                    'milestone' => $milestone,
                                ])
                            @empty
                                <div class="text-center py-8 text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="text-sm">Tidak ada milestone</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Delayed Column --}}
                <div class="flex-1 min-w-[300px]">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 h-full flex flex-col">
                        <div class="p-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                    <h3 class="font-semibold text-gray-900">Delayed</h3>
                                    <span
                                        class="px-2 py-0.5 bg-red-100 text-red-600 text-xs font-medium rounded-full">{{ $milestones->where('status', 'delayed')->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 space-y-3 flex-1 overflow-y-auto max-h-[calc(100vh-300px)]">
                            @forelse($milestones->where('status', 'delayed') as $milestone)
                                @include('admin.timeline.milestone.partials.kanban-card', [
                                    'milestone' => $milestone,
                                ])
                            @empty
                                <div class="text-center py-8 text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="text-sm">Tidak ada milestone</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Cancelled Column --}}
                <div class="flex-1 min-w-[300px]">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 h-full flex flex-col">
                        <div class="p-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 bg-gray-600 rounded-full"></div>
                                    <h3 class="font-semibold text-gray-900">Cancelled</h3>
                                    <span
                                        class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">{{ $milestones->where('status', 'cancelled')->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 space-y-3 flex-1 overflow-y-auto max-h-[calc(100vh-300px)]">
                            @forelse($milestones->where('status', 'cancelled') as $milestone)
                                @include('admin.timeline.milestone.partials.kanban-card', [
                                    'milestone' => $milestone,
                                ])
                            @empty
                                <div class="text-center py-8 text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="text-sm">Tidak ada milestone</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modals --}}
    @include('admin.timeline.milestone.partials.modals')
@endsection
