@extends('admin.layouts.app')

@section('title', 'Detail Work Instruction')

@section('content')
    <div x-data="{ showPublishModal: false, showArchiveModal: false }">
        {{-- Page Header --}}
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.work-instructions.index') }}"
                        class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $workInstruction->title }}</h1>
                        <p class="text-sm text-gray-600 mt-1">{{ $workInstruction->instruction_code }} • Version
                            {{ $workInstruction->version }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @if ($workInstruction->status === 'draft')
                        <button @click="showPublishModal = true"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Publish</span>
                        </button>
                    @endif

                    @if ($workInstruction->status === 'published')
                        <button @click="showArchiveModal = true"
                            class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                            <span>Archive</span>
                        </button>
                    @endif

                    <a href="{{ route('admin.work-instructions.download', $workInstruction) }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span>Download</span>
                    </a>

                    @if ($workInstruction->status !== 'archived')
                        <a href="{{ route('admin.work-instructions.edit', $workInstruction) }}"
                            class="px-4 py-2 bg-[#0053C5] text-white rounded-lg hover:bg-[#003d8f] transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span>Edit</span>
                        </a>
                    @endif

                    @if ($workInstruction->status === 'draft')
                        <form action="{{ route('admin.work-instructions.destroy', $workInstruction) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus work instruction ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span>Hapus</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Status Badges --}}
            <div class="flex items-center gap-3">
                @php
                    $statusColors = [
                        'draft' => 'bg-gray-100 text-gray-700',
                        'published' => 'bg-green-100 text-green-700',
                        'archived' => 'bg-orange-100 text-orange-700',
                    ];
                    $categoryColors = [
                        'setup' => 'bg-blue-100 text-blue-700',
                        'execution' => 'bg-green-100 text-green-700',
                        'troubleshooting' => 'bg-red-100 text-red-700',
                        'maintenance' => 'bg-yellow-100 text-yellow-700',
                        'reporting' => 'bg-purple-100 text-purple-700',
                        'other' => 'bg-gray-100 text-gray-700',
                    ];
                    $difficultyColors = [
                        'easy' => 'bg-green-100 text-green-700',
                        'medium' => 'bg-yellow-100 text-yellow-700',
                        'hard' => 'bg-red-100 text-red-700',
                    ];
                @endphp
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$workInstruction->status] ?? 'bg-gray-100' }}">
                    {{ ucfirst($workInstruction->status) }}
                </span>
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $categoryColors[$workInstruction->category] ?? 'bg-gray-100' }}">
                    {{ ucfirst($workInstruction->category) }}
                </span>
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $difficultyColors[$workInstruction->difficulty_level] ?? 'bg-gray-100' }}">
                    {{ ucfirst($workInstruction->difficulty_level) }}
                </span>
                @if ($workInstruction->estimated_time)
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-700">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $workInstruction->estimated_time_formatted }}
                    </span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Description --}}
                @if ($workInstruction->description)
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Deskripsi</h3>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $workInstruction->description }}</p>
                    </div>
                @endif

                {{-- Main Content --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Konten</h3>
                    <div class="prose max-w-none text-sm text-gray-700">
                        {!! nl2br(e($workInstruction->content)) !!}
                    </div>
                </div>

                {{-- Step-by-Step Instructions --}}
                @php
                    $steps = is_array($workInstruction->steps)
                        ? $workInstruction->steps
                        : json_decode($workInstruction->steps, true);
                @endphp
                @if ($steps && count($steps) > 0)
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Langkah-Langkah</h3>

                        <div class="space-y-4">
                            @foreach ($steps as $index => $step)
                                <div class="flex gap-4">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-8 h-8 bg-[#0053C5] text-white rounded-full flex items-center justify-center font-semibold text-sm">
                                            {{ $index + 1 }}
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-semibold text-gray-900 mb-1">
                                            {{ $step['title'] ?? 'Step ' . ($index + 1) }}</h4>
                                        <p class="text-sm text-gray-700 leading-relaxed">{{ $step['description'] ?? '' }}
                                        </p>
                                        @if (isset($step['image']) && $step['image'])
                                            <img src="{{ $step['image'] }}" alt="Step {{ $index + 1 }}"
                                                class="mt-3 rounded-lg border border-gray-200 max-w-md">
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Tools & Materials --}}
                @php
                    $tools = is_array($workInstruction->tools_required)
                        ? $workInstruction->tools_required
                        : json_decode($workInstruction->tools_required, true);
                    $materials = is_array($workInstruction->materials_required)
                        ? $workInstruction->materials_required
                        : json_decode($workInstruction->materials_required, true);
                @endphp
                @if (($tools && count($tools) > 0) || ($materials && count($materials) > 0))
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if ($tools && count($tools) > 0)
                            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tools/Peralatan</h3>
                                <ul class="space-y-2">
                                    @foreach ($tools as $tool)
                                        <li class="flex items-center gap-2 text-sm text-gray-700">
                                            <svg class="w-4 h-4 text-[#0053C5]" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $tool }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if ($materials && count($materials) > 0)
                            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Materials/Bahan</h3>
                                <ul class="space-y-2">
                                    @foreach ($materials as $material)
                                        <li class="flex items-center gap-2 text-sm text-gray-700">
                                            <svg class="w-4 h-4 text-[#0053C5]" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $material }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Safety & Precautions --}}
                @php
                    $precautions = is_array($workInstruction->precautions)
                        ? $workInstruction->precautions
                        : json_decode($workInstruction->precautions, true);
                @endphp
                @if ($workInstruction->safety_notes || ($precautions && count($precautions) > 0))
                    <div class="bg-red-50 rounded-xl shadow-sm p-6 border border-red-200">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-red-900 mb-3">Keamanan & Pencegahan</h3>

                                @if ($workInstruction->safety_notes)
                                    <p class="text-sm text-red-800 leading-relaxed mb-4">
                                        {{ $workInstruction->safety_notes }}</p>
                                @endif

                                @if ($precautions && count($precautions) > 0)
                                    <ul class="space-y-2">
                                        @foreach ($precautions as $precaution)
                                            <li class="flex items-start gap-2 text-sm text-red-800">
                                                <svg class="w-4 h-4 text-red-600 flex-shrink-0 mt-0.5" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 9v2m0 4h.01" />
                                                </svg>
                                                {{ $precaution }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Attachments --}}
                @php
                    $attachments = is_array($workInstruction->attachments)
                        ? $workInstruction->attachments
                        : json_decode($workInstruction->attachments, true);
                @endphp
                @if ($attachments && count($attachments) > 0)
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Lampiran</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach ($attachments as $attachment)
                                <a href="{{ Storage::url($attachment['path'] ?? '') }}" target="_blank"
                                    class="flex items-center gap-3 p-3 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-200 transition-colors">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $attachment['name'] ?? 'File' }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ isset($attachment['size']) ? round($attachment['size'] / 1024, 1) . ' KB' : '' }}
                                        </p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Notes --}}
                @if ($workInstruction->notes)
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Catatan</h3>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $workInstruction->notes }}</p>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Quick Info --}}
                <div class="bg-gradient-to-br from-[#0053C5] to-[#003d8f] rounded-xl shadow-sm p-6 text-white">
                    <h3 class="text-lg font-semibold mb-4">Quick Info</h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-blue-100">Code</span>
                            <span class="font-mono font-bold">{{ $workInstruction->instruction_code }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-blue-100">Version</span>
                            <span class="font-semibold">v{{ $workInstruction->version }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-blue-100">Effective</span>
                            <span
                                class="font-semibold">{{ $workInstruction->effective_date ? $workInstruction->effective_date->format('d M Y') : '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-blue-100">Views</span>
                            <span class="font-semibold">{{ number_format($workInstruction->view_count) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-blue-100">Downloads</span>
                            <span class="font-semibold">{{ number_format($workInstruction->download_count) }}</span>
                        </div>
                    </div>
                </div>

                {{-- SOP Reference --}}
                @if ($workInstruction->sop)
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Referensi SOP</h3>
                        <div class="p-3 bg-purple-50 rounded-lg border border-purple-100">
                            <p class="text-sm font-semibold text-purple-900">{{ $workInstruction->sop->sop_code }}</p>
                            <p class="text-xs text-purple-700 mt-1">{{ $workInstruction->sop->title }}</p>
                        </div>
                    </div>
                @endif

                {{-- Creator Info --}}
                @if ($workInstruction->creator)
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Dibuat Oleh</h3>
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-[#0053C5] text-white rounded-full flex items-center justify-center font-semibold">
                                {{ substr($workInstruction->creator->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $workInstruction->creator->name }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $workInstruction->created_at ? $workInstruction->created_at->format('d M Y H:i') : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Approval Info --}}
                @if ($workInstruction->status === 'published' && $workInstruction->approver)
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Diapprove Oleh</h3>
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center font-semibold">
                                {{ substr($workInstruction->approver->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $workInstruction->approver->name }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $workInstruction->approved_at ? $workInstruction->approved_at->format('d M Y H:i') : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Metadata --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Metadata</h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-start">
                            <span class="text-gray-500">Dibuat</span>
                            <span
                                class="text-gray-900 font-medium text-right">{{ $workInstruction->created_at ? $workInstruction->created_at->format('d M Y H:i') : '-' }}</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-gray-500">Terakhir diubah</span>
                            <span
                                class="text-gray-900 font-medium text-right">{{ $workInstruction->updated_at ? $workInstruction->updated_at->format('d M Y H:i') : '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Publish Modal --}}
        <div x-show="showPublishModal" x-cloak
            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
            @click.self="showPublishModal = false">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6" @click.stop>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Publish Work Instruction</h3>
                    <button @click="showPublishModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="mb-6 p-4 bg-green-50 rounded-lg border border-green-200">
                    <p class="text-sm text-green-800">Work Instruction akan dipublish dan tersedia untuk digunakan.</p>
                </div>

                <form action="{{ route('admin.work-instructions.publish', $workInstruction) }}" method="POST">
                    @csrf
                    <div class="flex items-center gap-3">
                        <button type="button" @click="showPublishModal = false"
                            class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            Publish
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Archive Modal --}}
        <div x-show="showArchiveModal" x-cloak
            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
            @click.self="showArchiveModal = false">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6" @click.stop>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Archive Work Instruction</h3>
                    <button @click="showArchiveModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="mb-6 p-4 bg-orange-50 rounded-lg border border-orange-200">
                    <p class="text-sm text-orange-800">Work Instruction akan diarsip dan tidak dapat diedit lagi.</p>
                </div>

                <form action="{{ route('admin.work-instructions.archive', $workInstruction) }}" method="POST">
                    @csrf
                    <div class="flex items-center gap-3">
                        <button type="button" @click="showArchiveModal = false"
                            class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                            Archive
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
