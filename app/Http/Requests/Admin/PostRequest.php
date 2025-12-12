<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Post;

class PostRequest extends FormRequest
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
        $postId = $this->route('post') ? $this->route('post')->id : null;
        $isUpdate = $this->isMethod('PATCH') || $this->isMethod('PUT');

        return [
            // Basic Info
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            
            // Featured Image - Required on create, optional on update
            'featured_image' => $isUpdate
                ? 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
                : 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            
            'category_id' => 'required|exists:categories,id',
            
            // SEO
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            
            // Status & Publishing
            'status' => 'required|in:draft,published,scheduled,archived',
            'published_at' => 'nullable|date',
            'scheduled_at' => 'nullable|date|after:now',
            
            // Features
            'is_featured' => 'boolean',
            'is_sticky' => 'boolean',
            'allow_comments' => 'boolean',
            
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
            'title' => 'Judul Post',
            'excerpt' => 'Ringkasan',
            'content' => 'Konten',
            'featured_image' => 'Gambar Utama',
            'category_id' => 'Kategori',
            'meta_title' => 'Meta Title (SEO)',
            'meta_description' => 'Meta Description (SEO)',
            'meta_keywords' => 'Meta Keywords (SEO)',
            'status' => 'Status',
            'published_at' => 'Tanggal Publish',
            'scheduled_at' => 'Jadwal Publish',
            'is_featured' => 'Post Unggulan',
            'is_sticky' => 'Post Sticky',
            'allow_comments' => 'Izinkan Komentar',
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
            'title.required' => 'Judul post wajib diisi.',
            'title.max' => 'Judul post maksimal 255 karakter.',
            'excerpt.max' => 'Ringkasan maksimal 500 karakter.',
            'content.required' => 'Konten post wajib diisi.',
            'featured_image.required' => 'Gambar utama wajib diupload.',
            'featured_image.image' => 'File harus berupa gambar.',
            'featured_image.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'featured_image.max' => 'Ukuran gambar maksimal 2MB.',
            'category_id.required' => 'Kategori post wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'meta_title.max' => 'Meta title maksimal 255 karakter.',
            'meta_description.max' => 'Meta description maksimal 500 karakter.',
            'meta_keywords.max' => 'Meta keywords maksimal 255 karakter.',
            'status.required' => 'Status post wajib dipilih.',
            'status.in' => 'Status post tidak valid.',
            'published_at.date' => 'Format tanggal publish tidak valid.',
            'scheduled_at.date' => 'Format tanggal jadwal tidak valid.',
            'scheduled_at.after' => 'Jadwal publish harus setelah waktu sekarang.',
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
            'is_featured' => $this->boolean('is_featured'),
            'is_sticky' => $this->boolean('is_sticky'),
            'allow_comments' => $this->boolean('allow_comments', true), // Default true
        ]);
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {
        // Convert empty strings to null for nullable fields
        $nullableFields = [
            'excerpt',
            'meta_title',
            'meta_description',
            'meta_keywords',
            'published_at',
            'scheduled_at',
        ];

        $validated = $this->validated();
        
        foreach ($nullableFields as $field) {
            if (isset($validated[$field]) && $validated[$field] === '') {
                $validated[$field] = null;
            }
        }

        $this->replace($validated);
    }
}