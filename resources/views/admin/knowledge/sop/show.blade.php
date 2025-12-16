@extends('admin.layouts.app')

@section('title', 'View SOP')

@section('content')
    <div x-data="{
        showApproveModal: false,
        showRejectModal: false,
        showVersionModal: false,
        rejectionReason: '',
        versionNotes: ''
    }">
        {{-- Header --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.sops.index') }}"
                    class="w-10 h-10 flex items-center justify-center rounded-lg bg-white border border-gray-300 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $sop->title }}</h1>
                        <span
                            class="px-2 py-1 text-xs font-medium rounded-full
                        @if ($sop->status == 'published') bg-green-100 text-green-800
                        @elseif($sop->status == 'approved') bg-blue-100 text-blue-800
                        @elseif($sop->status == 'under_review') bg-yellow-100 text-yellow-800
                        @elseif($sop->status == 'archived') bg-gray-100 text-gray-800
                        @else bg-orange-100 text-orange-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $sop->status)) }}
                        </span>
                    </div>
                    <p class="text-gray-600 mt-1">{{ $sop->sop_code }} - v{{ $sop->version }}</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                {{-- Actions based on status --}}
                @if ($sop->status === 'under_review')
                    <button @click="showApproveModal = true"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Approve
                    </button>
                    <button @click="showRejectModal = true"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Reject
                    </button>
                @endif

                @if ($sop->status === 'approved')
                    <form action="{{ route('admin.sops.publish', $sop) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 bg-[#0053C5] text-white rounded-lg hover:bg-[#004AB0] transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            Publish
                        </button>
                    </form>
                @endif

                @if ($sop->status === 'published')
                    <button @click="showVersionModal = true"
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                        </svg>
                        New Version
                    </button>
                    <form action="{{ route('admin.sops.archive', $sop) }}" method="POST" class="inline"
                        onsubmit="return confirm('Are you sure you want to archive this SOP?')">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                            Archive
                        </button>
                    </form>
                @endif

                @if (in_array($sop->status, ['draft', 'under_review']))
                    <a href="{{ route('admin.sops.edit', $sop) }}"
                        class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                @endif

                <form action="{{ route('admin.sops.download', $sop) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download PDF
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Information --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">SOP Code</p>
                            <p class="text-base font-medium text-gray-900">{{ $sop->sop_code }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Version</p>
                            <p class="text-base font-medium text-gray-900">v{{ $sop->version }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Category</p>
                            <span
                                class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                            @if ($sop->category == 'event_management') bg-blue-100 text-blue-800
                            @elseif($sop->category == 'finance') bg-green-100 text-green-800
                            @elseif($sop->category == 'registration') bg-purple-100 text-purple-800
                            @elseif($sop->category == 'documentation') bg-yellow-100 text-yellow-800
                            @elseif($sop->category == 'emergency') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $sop->category)) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Status</p>
                            <span
                                class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                            @if ($sop->status == 'published') bg-green-100 text-green-800
                            @elseif($sop->status == 'approved') bg-blue-100 text-blue-800
                            @elseif($sop->status == 'under_review') bg-yellow-100 text-yellow-800
                            @elseif($sop->status == 'archived') bg-gray-100 text-gray-800
                            @else bg-orange-100 text-orange-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $sop->status)) }}
                            </span>
                        </div>
                    </div>

                    @if ($sop->purpose)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm text-gray-600 mb-2">Purpose</p>
                            <p class="text-gray-900">{{ $sop->purpose }}</p>
                        </div>
                    @endif

                    @if ($sop->scope)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm text-gray-600 mb-2">Scope</p>
                            <p class="text-gray-900">{{ $sop->scope }}</p>
                        </div>
                    @endif
                </div>

                {{-- Content --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Content</h2>
                    <div class="prose max-w-none">
                        {!! $sop->content !!}
                    </div>
                </div>

                {{-- Procedures --}}
                @if ($sop->procedures && count($sop->procedures) > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Procedures (Step-by-Step)</h2>
                        <div class="space-y-4">
                            @foreach ($sop->procedures as $procedure)
                                <div class="border-l-4 border-[#0053C5] pl-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <div
                                            class="w-6 h-6 rounded-full bg-[#0053C5] text-white flex items-center justify-center text-xs font-semibold">
                                            {{ $procedure['step'] }}
                                        </div>
                                        <p class="font-medium text-gray-900">Step {{ $procedure['step'] }}</p>
                                    </div>
                                    <p class="text-gray-700 mb-1">{{ $procedure['description'] }}</p>
                                    @if (!empty($procedure['notes']))
                                        <p class="text-sm text-gray-600 italic">Note: {{ $procedure['notes'] }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Responsibilities --}}
                @if ($sop->responsibilities && count($sop->responsibilities) > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Responsibilities</h2>
                        <div class="space-y-4">
                            @foreach ($sop->responsibilities as $responsibility)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="font-medium text-gray-900 mb-2">{{ $responsibility['role'] }}</p>
                                    <p class="text-sm text-gray-700">{{ $responsibility['tasks'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Related Documents --}}
                @if (
                    ($sop->related_forms && count($sop->related_forms) > 0) ||
                        ($sop->related_templates && count($sop->related_templates) > 0))
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Related Documents</h2>

                        @if ($sop->related_forms && count($sop->related_forms) > 0)
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-700 mb-2">Forms</p>
                                <div class="space-y-2">
                                    @foreach ($sop->related_forms as $form)
                                        <div class="flex items-center gap-2 p-2 bg-blue-50 rounded-lg">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <span class="text-sm text-gray-900">{{ $form['name'] }}</span>
                                            @if (!empty($form['reference']))
                                                <span class="text-xs text-gray-600">({{ $form['reference'] }})</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($sop->related_templates && count($sop->related_templates) > 0)
                            <div>
                                <p class="text-sm font-medium text-gray-700 mb-2">Templates</p>
                                <div class="space-y-2">
                                    @foreach ($sop->related_templates as $template)
                                        <div class="flex items-center gap-2 p-2 bg-purple-50 rounded-lg">
                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                                            </svg>
                                            <span class="text-sm text-gray-900">{{ $template['name'] }}</span>
                                            @if (!empty($template['reference']))
                                                <span class="text-xs text-gray-600">({{ $template['reference'] }})</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Attachments --}}
                @if ($sop->attachments && count($sop->attachments) > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Attachments</h2>
                        <div class="space-y-2">
                            @foreach ($sop->attachments as $attachment)
                                <a href="{{ Storage::url($attachment['path']) }}" target="_blank"
                                    class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $attachment['name'] }}</p>
                                            <p class="text-xs text-gray-500">
                                                {{ number_format($attachment['size'] / 1024, 2) }} KB</p>
                                        </div>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Version History --}}
                @if ($sop->versions->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Version History</h2>
                        <div class="space-y-3">
                            @foreach ($sop->versions as $version)
                                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                                    <div
                                        class="w-8 h-8 rounded-full bg-[#0053C5] text-white flex items-center justify-center text-xs font-semibold flex-shrink-0">
                                        v{{ $version->version }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900">Version {{ $version->version }}
                                            </p>
                                            <span
                                                class="text-xs text-gray-500">{{ $version->created_at->format('d M Y') }}</span>
                                        </div>
                                        @if ($version->version_notes)
                                            <p class="text-sm text-gray-600 mt-1">{{ $version->version_notes }}</p>
                                        @endif
                                        <a href="{{ route('admin.sops.show', $version) }}"
                                            class="text-xs text-[#0053C5] hover:underline mt-1 inline-block">
                                            View this version →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Dates --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Dates</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Effective Date</p>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $sop->effective_date ? $sop->effective_date->format('d M Y') : '-' }}
                            </p>
                        </div>
                        @if ($sop->review_date)
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Review Date</p>
                                <p class="text-sm font-medium text-gray-900">{{ $sop->review_date->format('d M Y') }}</p>
                            </div>
                        @endif
                        @if ($sop->expiry_date)
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Expiry Date</p>
                                <p class="text-sm font-medium text-gray-900">{{ $sop->expiry_date->format('d M Y') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- People --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">People</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Created By</p>
                            <p class="text-sm font-medium text-gray-900">{{ $sop->creator->name }}</p>
                            <p class="text-xs text-gray-500">{{ $sop->created_at->format('d M Y H:i') }}</p>
                        </div>
                        @if ($sop->reviewer)
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Reviewed By</p>
                                <p class="text-sm font-medium text-gray-900">{{ $sop->reviewer->name }}</p>
                                <p class="text-xs text-gray-500">{{ $sop->reviewed_at->format('d M Y H:i') }}</p>
                            </div>
                        @endif
                        @if ($sop->approver)
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Approved By</p>
                                <p class="text-sm font-medium text-gray-900">{{ $sop->approver->name }}</p>
                                <p class="text-xs text-gray-500">{{ $sop->approved_at->format('d M Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Stats --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h2>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Views</span>
                            <span class="text-sm font-medium text-gray-900">{{ $sop->view_count }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Downloads</span>
                            <span class="text-sm font-medium text-gray-900">{{ $sop->download_count }}</span>
                        </div>
                    </div>
                </div>

                {{-- Notes --}}
                @if ($sop->notes)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Notes</h2>
                        <p class="text-sm text-gray-700">{{ $sop->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Approve Modal --}}
        <div x-show="showApproveModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" @click="showApproveModal = false"></div>
                <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Approve SOP</h3>
                    <p class="text-sm text-gray-600 mb-4">Are you sure you want to approve this SOP?</p>
                    <form action="{{ route('admin.sops.approve', $sop) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Approval Notes (Optional)</label>
                            <textarea name="approval_notes" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"></textarea>
                        </div>
                        <div class="flex gap-3">
                            <button type="submit"
                                class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                Approve
                            </button>
                            <button type="button" @click="showApproveModal = false"
                                class="flex-1 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Reject Modal --}}
        <div x-show="showRejectModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" @click="showRejectModal = false"></div>
                <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Reject SOP</h3>
                    <p class="text-sm text-gray-600 mb-4">Please provide a reason for rejection:</p>
                    <form action="{{ route('admin.sops.reject', $sop) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <textarea name="rejection_reason" rows="3" required x-model="rejectionReason"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                                placeholder="Explain why this SOP needs revision..."></textarea>
                        </div>
                        <div class="flex gap-3">
                            <button type="submit"
                                class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                Reject
                            </button>
                            <button type="button" @click="showRejectModal = false; rejectionReason = ''"
                                class="flex-1 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- New Version Modal --}}
        <div x-show="showVersionModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" @click="showVersionModal = false"></div>
                <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Create New Version</h3>
                    <p class="text-sm text-gray-600 mb-4">What changes are being made in this new version?</p>
                    <form action="{{ route('admin.sops.create-version', $sop) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <textarea name="version_notes" rows="3" required x-model="versionNotes"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                                placeholder="Describe what's new in this version..."></textarea>
                        </div>
                        <div class="flex gap-3">
                            <button type="submit"
                                class="flex-1 px-4 py-2 bg-[#0053C5] text-white rounded-lg hover:bg-[#004AB0] transition-colors">
                                Create Version
                            </button>
                            <button type="button" @click="showVersionModal = false; versionNotes = ''"
                                class="flex-1 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
