<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
        $eventId = $this->route('event') ? $this->route('event')->id : null;

        return [
            // Basic Info
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'full_description' => 'nullable|string',
            'featured_image' => $this->isMethod('POST') 
                ? 'required|image|mimes:jpeg,png,jpg,webp|max:2048'
                : 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'category_id' => 'required|exists:categories,id',
            
            // Location
            'location' => 'required|string|max:255',
            'location_maps_url' => 'nullable|url|max:500',
            
            // Date & Time
            'start_datetime' => 'required|date|after_or_equal:today',
            'end_datetime' => 'required|date|after:start_datetime',
            'timezone' => 'nullable|string|max:50',
            
            // Registration
            'is_registration_open' => 'boolean',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date|after_or_equal:registration_start',
            'max_participants' => 'nullable|integer|min:1',
            
            // Pricing
            'is_free' => 'boolean',
            'price' => 'nullable|numeric|min:0|required_if:is_free,false',
            
            // Additional Info
            'registration_fields' => 'nullable|array',
            'requirements' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            
            // Status & Features
            'status' => 'required|in:draft,published,cancelled,completed',
            'is_featured' => 'boolean',
            
            // SEO
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            
            // Relations
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            
            // Gallery
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    /**
     * Get custom attribute names.
     */
    public function attributes(): array
    {
        return [
            'title' => 'Judul Event',
            'description' => 'Deskripsi Singkat',
            'full_description' => 'Deskripsi Lengkap',
            'featured_image' => 'Gambar Utama',
            'category_id' => 'Kategori',
            'location' => 'Lokasi',
            'location_maps_url' => 'URL Google Maps',
            'start_datetime' => 'Tanggal & Waktu Mulai',
            'end_datetime' => 'Tanggal & Waktu Selesai',
            'timezone' => 'Zona Waktu',
            'is_registration_open' => 'Status Pendaftaran',
            'registration_start' => 'Pendaftaran Dibuka',
            'registration_end' => 'Pendaftaran Ditutup',
            'max_participants' => 'Maksimal Peserta',
            'is_free' => 'Event Gratis',
            'price' => 'Harga',
            'registration_fields' => 'Field Pendaftaran',
            'requirements' => 'Persyaratan',
            'contact_person' => 'Narahubung',
            'contact_phone' => 'No. Telepon',
            'contact_email' => 'Email Kontak',
            'status' => 'Status Event',
            'is_featured' => 'Event Unggulan',
            'meta_title' => 'Meta Title (SEO)',
            'meta_description' => 'Meta Description (SEO)',
            'tags' => 'Tags',
            'gallery_images' => 'Gambar Gallery',
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul event wajib diisi.',
            'title.max' => 'Judul event maksimal 255 karakter.',
            'description.required' => 'Deskripsi singkat wajib diisi.',
            'description.max' => 'Deskripsi singkat maksimal 500 karakter.',
            'featured_image.required' => 'Gambar utama wajib diupload.',
            'featured_image.image' => 'File harus berupa gambar.',
            'featured_image.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'featured_image.max' => 'Ukuran gambar maksimal 2MB.',
            'category_id.required' => 'Kategori event wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'location.required' => 'Lokasi event wajib diisi.',
            'location_maps_url.url' => 'URL Google Maps tidak valid.',
            'start_datetime.required' => 'Tanggal & waktu mulai wajib diisi.',
            'start_datetime.after_or_equal' => 'Tanggal mulai tidak boleh kurang dari hari ini.',
            'end_datetime.required' => 'Tanggal & waktu selesai wajib diisi.',
            'end_datetime.after' => 'Tanggal selesai harus setelah tanggal mulai.',
            'registration_end.after_or_equal' => 'Tanggal penutupan pendaftaran harus setelah tanggal pembukaan.',
            'max_participants.integer' => 'Maksimal peserta harus berupa angka.',
            'max_participants.min' => 'Maksimal peserta minimal 1 orang.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh negatif.',
            'price.required_if' => 'Harga wajib diisi untuk event berbayar.',
            'contact_email.email' => 'Format email tidak valid.',
            'status.required' => 'Status event wajib dipilih.',
            'status.in' => 'Status event tidak valid.',
            'tags.*.exists' => 'Tag yang dipilih tidak valid.',
            'gallery_images.*.image' => 'File gallery harus berupa gambar.',
            'gallery_images.*.mimes' => 'Format gambar gallery harus jpeg, png, jpg, atau webp.',
            'gallery_images.*.max' => 'Ukuran gambar gallery maksimal 2MB.',
        ];
    }

    /**
     * Prepare data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert checkboxes to boolean
        $this->merge([
            'is_registration_open' => $this->boolean('is_registration_open'),
            'is_free' => $this->boolean('is_free'),
            'is_featured' => $this->boolean('is_featured'),
        ]);

        // If free event, set price to 0
        if ($this->boolean('is_free')) {
            $this->merge(['price' => 0]);
        }
    }
}