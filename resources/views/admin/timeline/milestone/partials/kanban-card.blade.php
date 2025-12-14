<div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer group"
    data-milestone-id="{{ $milestone->id }}" data-milestone-name="{{ $milestone->name }}">
    {{-- Header --}}
    <div class="flex items-start justify-between mb-3">
        <div class="flex-1 min-w-0">
            <h4 class="text-sm font-semibold text-gray-900 truncate group-hover:text-primary transition-colors">
                {{ $milestone->name }}
            </h4>
            <p class="text-xs text-gray-500 mt-1">{{ $milestone->code }}</p>
        </div>
        @if ($milestone->is_verified)
            <svg class="w-5 h-5 text-green-500 flex-shrink-0 ml-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
        @endif
    </div>

    {{-- Event --}}
    <div class="flex items-center gap-2 mb-2">
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <span class="text-xs text-gray-600 truncate">{{ $milestone->event->name }}</span>
    </div>

    {{-- Target Date --}}
    <div class="flex items-center gap-2 mb-3">
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="text-xs text-gray-600">{{ $milestone->target_date->format('d M Y') }}</span>
        @if ($milestone->is_overdue)
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                Terlambat
            </span>
        @endif
    </div>

    {{-- Progress Bar --}}
    <div class="mb-3">
        <div class="flex items-center justify-between mb-1">
            <span class="text-xs text-gray-500">Progress</span>
            <span class="text-xs font-medium text-gray-700">{{ $milestone->progress_percentage ?? 0 }}%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-1.5">
            <div class="bg-primary h-1.5 rounded-full transition-all"
                style="width: {{ $milestone->progress_percentage ?? 0 }}%"></div>
        </div>
    </div>

    {{-- Priority Badge --}}
    <div class="flex items-center justify-between">
        @php
            $priorityConfig = [
                'low' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Low'],
                'medium' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Medium'],
                'high' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'label' => 'High'],
                'urgent' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Urgent'],
            ];
            $config = $priorityConfig[$milestone->priority];
        @endphp
        <span
            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }}">
            {{ $config['label'] }}
        </span>

        {{-- PIC Avatar --}}
        @if ($milestone->responsiblePerson)
            <div class="flex items-center gap-1" title="{{ $milestone->responsiblePerson->name }}">
                <div
                    class="w-6 h-6 bg-primary text-white rounded-full flex items-center justify-center text-xs font-medium">
                    {{ substr($milestone->responsiblePerson->name, 0, 1) }}
                </div>
            </div>
        @endif
    </div>

    {{-- Actions --}}
    <div class="mt-3 pt-3 border-t border-gray-100 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
        @if ($milestone->status != 'completed')
            <button
                @click.stop="window.dispatchEvent(new CustomEvent('open-modal', {detail: 'complete-milestone-{{ $milestone->id }}'}))"
                class="flex-1 text-xs px-2 py-1.5 bg-green-50 text-green-700 rounded hover:bg-green-100 transition-colors">
                Complete
            </button>
        @endif

        <button
            @click.stop="window.dispatchEvent(new CustomEvent('open-modal', {detail: 'edit-milestone-{{ $milestone->id }}'}))"
            class="flex-1 text-xs px-2 py-1.5 bg-blue-50 text-blue-700 rounded hover:bg-blue-100 transition-colors">
            Edit
        </button>
    </div>
</div>