<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Event;

class EventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'full_description' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'category_id' => ['required', 'exists:categories,id'],
            'location' => ['nullable', 'string', 'max:255'],
            'location_maps_url' => ['nullable', 'url', 'max:500'],
            'start_datetime' => ['required', 'date', 'after:now'],
            'end_datetime' => ['required', 'date', 'after:start_datetime'],
            'timezone' => ['nullable', 'string', 'max:50'],
            'is_registration_open' => ['boolean'],
            'registration_start' => ['nullable', 'date'],
            'registration_end' => ['nullable', 'date', 'after:registration_start', 'before:start_datetime'],
            'max_participants' => ['nullable', 'integer', 'min:1'],
            'is_free' => ['boolean'],
            'price' => ['required_if:is_free,false', 'nullable', 'numeric', 'min:0'],
            'registration_fields' => ['nullable', 'json'],
            'requirements' => ['nullable', 'string'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:20'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'status' => ['required', 'in:' . implode(',', [Event::STATUS_DRAFT, Event::STATUS_PUBLISHED, Event::STATUS_CANCELLED, Event::STATUS_COMPLETED])],
            'is_featured' => ['boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'tags' => ['nullable', 'string'],
            'gallery_images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul event wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'start_datetime.required' => 'Tanggal mulai wajib diisi.',
            'start_datetime.after' => 'Tanggal mulai harus di masa depan.',
            'end_datetime.required' => 'Tanggal selesai wajib diisi.',
            'end_datetime.after' => 'Tanggal selesai harus setelah tanggal mulai.',
            'registration_end.before' => 'Batas pendaftaran harus sebelum tanggal event.',
            'price.required_if' => 'Harga wajib diisi untuk event berbayar.',
        ];
    }
}