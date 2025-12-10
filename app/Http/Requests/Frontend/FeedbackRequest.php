<?php
// app/Http/Requests/Frontend/FeedbackRequest.php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class FeedbackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event_id' => ['nullable', 'exists:events,id'],
            'registration_id' => ['nullable', 'exists:event_registrations,id'],
            'type' => ['required', 'string', 'in:testimonial,suggestion,complaint,general'],
            'name' => ['required_without:user_id', 'string', 'max:255'],
            'email' => ['required_without:user_id', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'overall_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'event_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'facility_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'service_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10'],
            'is_anonymous' => ['boolean'],
            'attachments.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Jenis feedback wajib dipilih.',
            'message.required' => 'Pesan feedback wajib diisi.',
            'message.min' => 'Pesan minimal 10 karakter.',
            'overall_rating.min' => 'Rating minimal 1.',
            'overall_rating.max' => 'Rating maksimal 5.',
            'attachments.*.max' => 'Ukuran file maksimal 2MB.',
        ];
    }
}