<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\MeetingMinute;

class MeetingMinuteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            // Basic Info
            'event_id' => 'nullable|exists:events,id',
            'structure_id' => 'nullable|exists:committee_structures,id',
            'meeting_title' => 'required|string|max:255',
            'meeting_type' => 'required|in:' . implode(',', [
                MeetingMinute::TYPE_COORDINATION,
                MeetingMinute::TYPE_PLANNING,
                MeetingMinute::TYPE_EVALUATION,
                MeetingMinute::TYPE_EMERGENCY,
                MeetingMinute::TYPE_GENERAL,
                MeetingMinute::TYPE_OTHER,
            ]),

            // Schedule
            'meeting_date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'meeting_link' => 'nullable|url|max:500',
            'duration_minutes' => 'nullable|integer|min:1',

            // Participants
            'participants' => 'nullable|array',
            'participants.*' => 'exists:users,id',
            'absent_members' => 'nullable|array',
            'absent_members.*' => 'exists:users,id',
            'external_participants' => 'nullable|array',
            'external_participants.*.name' => 'required|string|max:255',
            'external_participants.*.organization' => 'nullable|string|max:255',
            'external_participants.*.email' => 'nullable|email|max:255',

            // Roles
            'chairman' => 'nullable|exists:users,id',
            'secretary' => 'nullable|exists:users,id',

            // Content
            'agenda' => 'nullable|string',
            'discussion_summary' => 'nullable|string',
            'decisions' => 'nullable|string',
            'action_items' => 'nullable|string',
            'next_meeting_agenda' => 'nullable|string',

            // Action Items List
            'action_items_list' => 'nullable|array',
            'action_items_list.*.task' => 'required|string',
            'action_items_list.*.assignee' => 'nullable|exists:users,id',
            'action_items_list.*.deadline' => 'nullable|date',
            'action_items_list.*.status' => 'nullable|in:pending,in_progress,completed',

            // Documents
            'document_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // 10MB
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,xlsx,xls|max:5120', // 5MB each

            // Next Meeting
            'next_meeting_date' => 'nullable|date|after:meeting_date',
            'next_meeting_location' => 'nullable|string|max:255',

            // Metadata
            'notes' => 'nullable|string',
        ];

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'event_id' => 'event',
            'structure_id' => 'struktur kepanitiaan',
            'meeting_title' => 'judul rapat',
            'meeting_type' => 'tipe rapat',
            'meeting_date' => 'tanggal rapat',
            'location' => 'lokasi',
            'meeting_link' => 'link meeting online',
            'duration_minutes' => 'durasi rapat',
            'participants' => 'peserta hadir',
            'absent_members' => 'peserta tidak hadir',
            'external_participants' => 'peserta eksternal',
            'chairman' => 'ketua rapat',
            'secretary' => 'sekretaris/notulis',
            'agenda' => 'agenda',
            'discussion_summary' => 'ringkasan diskusi',
            'decisions' => 'keputusan',
            'action_items' => 'tindak lanjut',
            'next_meeting_agenda' => 'agenda rapat berikutnya',
            'action_items_list' => 'daftar tindak lanjut',
            'document_file' => 'file dokumen',
            'attachments' => 'lampiran',
            'next_meeting_date' => 'tanggal rapat berikutnya',
            'next_meeting_location' => 'lokasi rapat berikutnya',
            'notes' => 'catatan',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'meeting_title.required' => 'Judul rapat wajib diisi.',
            'meeting_type.required' => 'Tipe rapat wajib dipilih.',
            'meeting_type.in' => 'Tipe rapat yang dipilih tidak valid.',
            'meeting_date.required' => 'Tanggal rapat wajib diisi.',
            'meeting_date.date' => 'Format tanggal rapat tidak valid.',
            'event_id.exists' => 'Event yang dipilih tidak ditemukan.',
            'structure_id.exists' => 'Struktur kepanitiaan yang dipilih tidak ditemukan.',
            'meeting_link.url' => 'Format link meeting tidak valid.',
            'duration_minutes.integer' => 'Durasi rapat harus berupa angka.',
            'duration_minutes.min' => 'Durasi rapat minimal 1 menit.',
            'participants.*.exists' => 'Peserta yang dipilih tidak valid.',
            'absent_members.*.exists' => 'Anggota tidak hadir yang dipilih tidak valid.',
            'chairman.exists' => 'Ketua rapat yang dipilih tidak ditemukan.',
            'secretary.exists' => 'Sekretaris yang dipilih tidak ditemukan.',
            'next_meeting_date.after' => 'Tanggal rapat berikutnya harus setelah tanggal rapat ini.',
            'document_file.mimes' => 'File dokumen harus berformat PDF, DOC, atau DOCX.',
            'document_file.max' => 'Ukuran file dokumen maksimal 10MB.',
            'attachments.*.mimes' => 'Lampiran harus berformat PDF, DOC, DOCX, JPG, JPEG, PNG, XLSX, atau XLS.',
            'attachments.*.max' => 'Ukuran setiap lampiran maksimal 5MB.',
            'action_items_list.*.task.required' => 'Task pada tindak lanjut wajib diisi.',
            'action_items_list.*.assignee.exists' => 'Penanggung jawab yang dipilih tidak valid.',
            'action_items_list.*.deadline.date' => 'Format deadline tidak valid.',
            'external_participants.*.name.required' => 'Nama peserta eksternal wajib diisi.',
            'external_participants.*.email.email' => 'Format email peserta eksternal tidak valid.',
        ];
    }
}
