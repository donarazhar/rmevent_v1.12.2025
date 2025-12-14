{{-- resources/views/admin/committee/structure/partials/tree-item.blade.php --}}

<div class="border border-gray-200 rounded-lg {{ $level > 0 ? 'ml-8 mt-2' : 'mb-2' }}" x-data="{ expanded: true }"
    data-structure-id="{{ $structure->id }}">

    {{-- Structure Header --}}
    <div class="p-4 {{ $structure->status === 'active' ? 'bg-white' : 'bg-gray-50' }}">
        <div class="flex items-start justify-between gap-4">
            {{-- Left Section --}}
            <div class="flex items-start gap-3 flex-1">
                {{-- Expand/Collapse Button --}}
                @if ($structure->children->count() > 0)
                    <button @click="expanded = !expanded" class="mt-1 p-1 hover:bg-gray-100 rounded transition-colors">
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-90': expanded }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                @else
                    <div class="w-7"></div>
                @endif

                {{-- Structure Info --}}
                <div class="flex-1">
                    <div class="flex items-start gap-3">
                        {{-- Icon --}}
                        <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>

                        {{-- Details --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="text-base font-semibold text-gray-900">{{ $structure->name }}</h3>
                                @if ($structure->code)
                                    <span class="px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-700 rounded">
                                        {{ $structure->code }}
                                    </span>
                                @endif
                                <span
                                    class="px-2 py-0.5 text-xs font-medium rounded
                                 {{ $structure->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($structure->status) }}
                                </span>
                            </div>
                            @if ($structure->description)
                                <p class="text-sm text-gray-600 mb-2">{{ $structure->description }}</p>
                            @endif

                            {{-- Leadership Info --}}
                            <div class="flex flex-wrap gap-4 text-sm">
                                @if ($structure->leader)
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span class="text-gray-600">Ketua:</span>
                                        <span class="font-medium text-gray-900">{{ $structure->leader->name }}</span>
                                    </div>
                                @endif

                                @if ($structure->viceLeader)
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span class="text-gray-600">Wakil:</span>
                                        <span
                                            class="font-medium text-gray-900">{{ $structure->viceLeader->name }}</span>
                                    </div>
                                @endif

                                @if ($structure->activeMembers->count() > 0)
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span class="text-gray-600">{{ $structure->activeMembers->count() }}
                                            anggota</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Contact Info --}}
                            @if ($structure->email || $structure->phone)
                                <div class="flex flex-wrap gap-3 mt-2 text-sm text-gray-600">
                                    @if ($structure->email)
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            <span>{{ $structure->email }}</span>
                                        </div>
                                    @endif
                                    @if ($structure->phone)
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            <span>{{ $structure->phone }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-1">
                {{-- Add Child Button --}}
                <button @click="$dispatch('add-child-structure', { parent: {{ $structure->id }} })"
                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Tambah Sub-Struktur">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </button>

                {{-- Edit Button - UPDATED ROUTE --}}
                <a href="{{ route('admin.committee.structure.edit', $structure->id) }}"
                    class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors" title="Edit">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>

                {{-- Delete Button --}}
                <button
                    @click="$dispatch('delete-structure', { id: {{ $structure->id }}, name: '{{ $structure->name }}' })"
                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Responsibilities & Authorities (Collapsible) --}}
        @if ($structure->responsibilities || $structure->authorities)
            <div class="mt-3 pt-3 border-t border-gray-200" x-data="{ showDetails: false }">
                <button @click="showDetails = !showDetails"
                    class="text-sm text-primary hover:text-primary-dark font-medium flex items-center gap-1">
                    <span x-text="showDetails ? 'Sembunyikan Detail' : 'Lihat Detail'"></span>
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': showDetails }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="showDetails" x-collapse class="mt-3 space-y-3">
                    @if ($structure->responsibilities)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Tanggung Jawab:</h4>
                            <ul class="list-disc list-inside space-y-1 text-sm text-gray-600">
                                @foreach ($structure->responsibilities as $responsibility)
                                    <li>{{ $responsibility }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($structure->authorities)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Wewenang:</h4>
                            <ul class="list-disc list-inside space-y-1 text-sm text-gray-600">
                                @foreach ($structure->authorities as $authority)
                                    <li>{{ $authority }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- Children Structures --}}
    @if ($structure->children->count() > 0)
        <div x-show="expanded" x-collapse class="p-4 pt-0 space-y-2">
            @foreach ($structure->children as $child)
                @include('admin.committee.structure.partials.tree-item', [
                    'structure' => $child,
                    'level' => $level + 1,
                ])
            @endforeach
        </div>
    @endif
</div>
