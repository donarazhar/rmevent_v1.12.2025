@extends('admin.layouts.app')

@section('title', 'Daftar Alokasi Budget')

@section('content')
    <div x-data="{
        showFilters: true
    }" class="space-y-6">
        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Alokasi Budget</h1>
                <p class="text-sm text-gray-600 mt-1">Kelola alokasi budget untuk berbagai kegiatan dan divisi</p>
            </div>
            <a href="{{ route('admin.budget-allocations.create') }}"
                class="inline-flex items-center justify-center px-4 py-2 bg-[#0053C5] text-white rounded-xl hover:bg-[#004AB0] transition-colors shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Alokasi Baru
            </a>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Total Alokasi --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">Total Alokasi</p>
                        <p class="text-2xl font-bold text-gray-900 mt-2">{{ $allocations->total() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Active --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">Active</p>
                        <p class="text-2xl font-bold text-green-600 mt-2">
                            {{ \App\Models\BudgetAllocation::where('status', 'active')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Depleted --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">Depleted</p>
                        <p class="text-2xl font-bold text-red-600 mt-2">
                            {{ \App\Models\BudgetAllocation::where('status', 'depleted')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Nilai --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">Total Nilai</p>
                        <p class="text-xl font-bold text-[#0053C5] mt-2">Rp
                            {{ number_format(\App\Models\BudgetAllocation::sum('allocated_amount'), 0, ',', '.') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#0053C5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <h3 class="font-semibold text-gray-900">Filter</h3>
                </div>
                <button @click="showFilters = !showFilters" class="text-gray-500 hover:text-gray-700">
                    <svg x-show="!showFilters" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    <svg x-show="showFilters" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                </button>
            </div>

            <div x-show="showFilters" x-collapse class="p-4">
                <form method="GET" action="{{ route('admin.budget-allocations.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
                        {{-- Budget Filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Budget</label>
                            <select name="budget_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                <option value="">Semua Budget</option>
                                @foreach ($budgets as $budget)
                                    <option value="{{ $budget->id }}"
                                        {{ request('budget_id') == $budget->id ? 'selected' : '' }}>
                                        {{ $budget->budget_code }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Event Filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Event</label>
                            <select name="event_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                <option value="">Semua Event</option>
                                @foreach ($events as $event)
                                    <option value="{{ $event->id }}"
                                        {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                        {{ $event->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Structure Filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Struktur</label>
                            <select name="structure_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                <option value="">Semua Struktur</option>
                                @foreach ($structures as $structure)
                                    <option value="{{ $structure->id }}"
                                        {{ request('structure_id') == $structure->id ? 'selected' : '' }}>
                                        {{ $structure->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Type Filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                            <select name="allocation_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                <option value="">Semua Tipe</option>
                                <option value="operational"
                                    {{ request('allocation_type') == 'operational' ? 'selected' : '' }}>Operasional
                                </option>
                                <option value="program" {{ request('allocation_type') == 'program' ? 'selected' : '' }}>
                                    Program</option>
                                <option value="project" {{ request('allocation_type') == 'project' ? 'selected' : '' }}>
                                    Project</option>
                                <option value="reserve" {{ request('allocation_type') == 'reserve' ? 'selected' : '' }}>
                                    Cadangan</option>
                                <option value="contingency"
                                    {{ request('allocation_type') == 'contingency' ? 'selected' : '' }}>Kontingensi
                                </option>
                            </select>
                        </div>

                        {{-- Status Filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                <option value="">Semua Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="depleted" {{ request('status') == 'depleted' ? 'selected' : '' }}>Depleted
                                </option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                            </select>
                        </div>
                    </div>

                    {{-- Search --}}
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari berdasarkan judul atau kode alokasi..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                        </div>
                        <button type="submit"
                            class="px-6 py-2 bg-[#0053C5] text-white rounded-lg hover:bg-[#004AB0] transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Filter
                        </button>
                        @if (request()->hasAny(['budget_id', 'event_id', 'structure_id', 'allocation_type', 'status', 'search']))
                            <a href="{{ route('admin.budget-allocations.index') }}"
                                class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Allocations Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-200">
                <h3 class="font-semibold text-gray-900">Daftar Alokasi Budget</h3>
            </div>

            @if ($allocations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                    Kode</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                    Judul</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                    Budget</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                    Tipe</th>
                                <th
                                    class="px-4 py-3 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">
                                    Dialokasikan</th>
                                <th
                                    class="px-4 py-3 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">
                                    Terpakai</th>
                                <th
                                    class="px-4 py-3 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">
                                    Sisa</th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">
                                    Utilisasi</th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($allocations as $allocation)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <a href="{{ route('admin.budget-allocations.show', $allocation) }}"
                                            class="text-[#0053C5] hover:underline font-medium">
                                            {{ $allocation->allocation_code }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ Str::limit($allocation->title, 30) }}</div>
                                        @if ($allocation->structure)
                                            <div class="text-xs text-gray-500">{{ $allocation->structure->name }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $allocation->budget->budget_code }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                            {{ ucfirst($allocation->allocation_type) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right text-sm text-gray-900">Rp
                                        {{ number_format($allocation->allocated_amount, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right text-sm text-red-600">Rp
                                        {{ number_format($allocation->spent_amount, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right text-sm text-green-600">Rp
                                        {{ number_format($allocation->remaining_amount, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center">
                                            <div class="w-full max-w-[100px]">
                                                <div class="bg-gray-200 rounded-full h-2 overflow-hidden">
                                                    <div class="h-full {{ $allocation->utilization_rate > 80 ? 'bg-yellow-500' : 'bg-green-500' }}"
                                                        style="width: {{ min($allocation->utilization_rate, 100) }}%">
                                                    </div>
                                                </div>
                                                <div class="text-xs text-center text-gray-600 mt-1">
                                                    {{ number_format($allocation->utilization_rate, 0) }}%</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if ($allocation->status === 'active')
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                                        @elseif($allocation->status === 'depleted')
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Depleted</span>
                                        @else
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">{{ ucfirst($allocation->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-1">
                                            <a href="{{ route('admin.budget-allocations.show', $allocation) }}"
                                                class="p-1 text-blue-600 hover:bg-blue-50 rounded">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            @if (in_array($allocation->status, ['active', 'pending']))
                                                <a href="{{ route('admin.budget-allocations.edit', $allocation) }}"
                                                    class="p-1 text-yellow-600 hover:bg-yellow-50 rounded">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 border-t border-gray-200">
                            <tr class="font-medium">
                                <td colspan="4" class="px-4 py-3 text-right text-gray-700">Total:</td>
                                <td class="px-4 py-3 text-right text-gray-900">Rp
                                    {{ number_format($allocations->sum('allocated_amount'), 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right text-red-600">Rp
                                    {{ number_format($allocations->sum('spent_amount'), 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right text-green-600">Rp
                                    {{ number_format($allocations->sum('remaining_amount'), 0, ',', '.') }}</td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="p-4 border-t border-gray-200">
                    {{ $allocations->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada alokasi budget</h3>
                    <p class="text-sm text-gray-600 mb-4">Belum ada data alokasi budget yang sesuai dengan filter Anda.</p>
                    @if (request()->hasAny(['budget_id', 'event_id', 'structure_id', 'allocation_type', 'status', 'search']))
                        <a href="{{ route('admin.budget-allocations.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-[#0053C5] text-white rounded-lg hover:bg-[#004AB0] transition-colors">
                            Reset Filter
                        </a>
                    @else
                        <a href="{{ route('admin.budget-allocations.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-[#0053C5] text-white rounded-lg hover:bg-[#004AB0] transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Buat Alokasi Pertama
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
