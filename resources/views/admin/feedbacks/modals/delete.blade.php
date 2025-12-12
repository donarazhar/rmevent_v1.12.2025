<div id="deleteModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        {{-- Modal Header --}}
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 bg-red-100 rounded-full">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900">Hapus Feedback</h3>
            </div>
            <button type="button" onclick="closeModal('deleteModal')"
                class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Modal Body --}}
        <div class="p-6">
            <p class="text-gray-700 mb-2">
                Apakah Anda yakin ingin menghapus feedback
                @if (isset($feedback))
                    dari <span
                        class="font-semibold">{{ $feedback->name ?? ($feedback->user->name ?? 'Anonymous') }}</span>
                @else
                    <span id="delete_feedback_subject" class="font-semibold"></span>
                @endif
                ?
            </p>
            <p class="text-sm text-gray-600">
                Tindakan ini tidak dapat dibatalkan. Feedback akan dihapus secara permanen dari database.
            </p>
        </div>

        {{-- Modal Footer --}}
        <form id="deleteForm" method="POST" action="">
            @csrf
            @method('DELETE')

            <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-200 bg-gray-50">
                <button type="button" onclick="closeModal('deleteModal')"
                    class="px-4 py-2 text-gray-700 hover:bg-gray-100 font-medium rounded-lg transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                    Ya, Hapus Feedback
                </button>
            </div>
        </form>
    </div>
</div>
