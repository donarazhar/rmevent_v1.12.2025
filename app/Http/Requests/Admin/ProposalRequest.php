<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Proposal;

class ProposalRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:' . implode(',', [
                Proposal::TYPE_EVENT,
                Proposal::TYPE_SPONSORSHIP,
                Proposal::TYPE_PARTNERSHIP,
                Proposal::TYPE_FUNDING,
                Proposal::TYPE_PROJECT,
                Proposal::TYPE_OTHER,
            ]),

            // Content
            'executive_summary' => 'nullable|string',
            'background' => 'nullable|string',
            'objectives' => 'nullable|string',
            'methodology' => 'nullable|string',
            'timeline' => 'nullable|string',
            'budget_overview' => 'nullable|string',
            'expected_outcomes' => 'nullable|string',

            // Recipient Info
            'submitted_to' => 'nullable|string|max:255',
            'recipient_contact' => 'nullable|string|max:255',
            'recipient_email' => 'nullable|email|max:255',

            // Financial
            'requested_amount' => 'nullable|numeric|min:0',
            'response_deadline' => 'nullable|date|after_or_equal:today',

            // Documents
            'document_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // 10MB
            'supporting_documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB each

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
            'title' => 'judul proposal',
            'description' => 'deskripsi',
            'type' => 'tipe proposal',
            'executive_summary' => 'ringkasan eksekutif',
            'background' => 'latar belakang',
            'objectives' => 'tujuan',
            'methodology' => 'metodologi',
            'timeline' => 'timeline',
            'budget_overview' => 'overview budget',
            'expected_outcomes' => 'hasil yang diharapkan',
            'submitted_to' => 'diajukan kepada',
            'recipient_contact' => 'kontak penerima',
            'recipient_email' => 'email penerima',
            'requested_amount' => 'jumlah yang diminta',
            'response_deadline' => 'batas waktu respon',
            'document_file' => 'file dokumen',
            'supporting_documents' => 'dokumen pendukung',
            'notes' => 'catatan',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul proposal wajib diisi.',
            'type.required' => 'Tipe proposal wajib dipilih.',
            'type.in' => 'Tipe proposal yang dipilih tidak valid.',
            'event_id.exists' => 'Event yang dipilih tidak ditemukan.',
            'structure_id.exists' => 'Struktur kepanitiaan yang dipilih tidak ditemukan.',
            'recipient_email.email' => 'Format email penerima tidak valid.',
            'requested_amount.numeric' => 'Jumlah yang diminta harus berupa angka.',
            'requested_amount.min' => 'Jumlah yang diminta tidak boleh negatif.',
            'response_deadline.after_or_equal' => 'Batas waktu respon harus hari ini atau setelahnya.',
            'document_file.mimes' => 'File dokumen harus berformat PDF, DOC, atau DOCX.',
            'document_file.max' => 'Ukuran file dokumen maksimal 10MB.',
            'supporting_documents.*.mimes' => 'Dokumen pendukung harus berformat PDF, DOC, DOCX, JPG, JPEG, atau PNG.',
            'supporting_documents.*.max' => 'Ukuran setiap dokumen pendukung maksimal 5MB.',
        ];
    }
}
